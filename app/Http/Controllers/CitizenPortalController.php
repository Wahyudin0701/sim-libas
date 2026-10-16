<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Complaint;
use App\Models\Event;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\WasteDeposit;
use App\Models\WasteWallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CitizenPortalController extends Controller
{
    /**
     * Dapatkan data Kartu Keluarga milik user yang sedang login
     */
    protected function getHousehold()
    {
        $user = Auth::user();
        $resident = $user->resident;

        if ($resident && $resident->household) {
            return $resident->household;
        }

        return Household::where('user_id', $user->id)->first();
    }

    /**
     * Dashboard Utama Warga (FR-DSH-02)
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $household = $this->getHousehold();
        $resident = $user->resident ?? $household?->kepalaKeluarga;

        // Tagihan belum bayar
        $invoices = $household ? Invoice::where('household_id', $household->id)
            ->where('status', 'belum_bayar')
            ->with('feeType')
            ->get() : collect();

        $totalTagihan = $invoices->sum('jumlah');

        // Status surat terakhir
        $latestLetter = $resident ? Letter::where('resident_id', $resident->id)
            ->with('letterType')
            ->latest('created_at')
            ->first() : null;

        // Saldo bank sampah
        $wallet = $household ? WasteWallet::where('household_id', $household->id)->first() : null;
        $saldoBankSampah = $wallet?->saldo ?? 0.00;

        // Pengumuman & Agenda
        $pengumuman = Announcement::where('is_published', true)->latest()->take(2)->get();
        $agenda = Event::where('tanggal_mulai', '>=', now())->orderBy('tanggal_mulai')->take(2)->get();

        return view('warga.dashboard', compact(
            'user',
            'household',
            'resident',
            'invoices',
            'totalTagihan',
            'latestLetter',
            'saldoBankSampah',
            'pengumuman',
            'agenda'
        ));
    }

    /**
     * Halaman Layanan Surat Pengantar Warga
     */
    public function letters(): View
    {
        $user = Auth::user();
        $household = $this->getHousehold();
        $resident = $user->resident ?? $household?->kepalaKeluarga;

        $residentIds = $household ? $household->residents->pluck('id') : ($resident ? [$resident->id] : []);

        $letters = Letter::whereIn('resident_id', $residentIds)
            ->with(['letterType', 'resident'])
            ->latest()
            ->paginate(10);

        $letterTypes = LetterType::where('is_active', true)->orderBy('urutan')->get();

        return view('warga.surat', compact('letters', 'letterTypes', 'household', 'resident'));
    }

    /**
     * Simpan Pengajuan Surat Pengantar Baru
     */
    public function storeLetter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'letter_type_id' => ['required', 'exists:letter_types,id'],
            'resident_id' => ['required', 'exists:residents,id'],
            'keperluan' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $letter = Letter::create([
            'letter_type_id' => $validated['letter_type_id'],
            'resident_id' => $validated['resident_id'],
            'keperluan' => $validated['keperluan'],
            'status' => 'diajukan',
            'qr_token' => bin2hex(random_bytes(16)),
        ]);

        return redirect()->route('warga.surat')->with('success', 'Permohonan surat pengantar berhasil dikirim! Menunggu verifikasi pengurus RT.');
    }

    /**
     * Halaman Tagihan Iuran & Pembayaran Warga
     */
    public function invoices(): View
    {
        $household = $this->getHousehold();

        $invoices = $household ? Invoice::where('household_id', $household->id)
            ->with(['feeType', 'payments'])
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->get() : collect();

        return view('warga.iuran', compact('invoices', 'household'));
    }

    /**
     * Simpan Bukti Pembayaran Iuran
     */
    public function storePayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1000'],
            'metode' => ['required', 'in:tunai,transfer'],
            'bukti' => ['nullable', 'image', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti-bayar', 'public');
        }

        Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => $request->jumlah,
            'metode' => $request->metode,
            'bukti_path' => $buktiPath,
            'catatan' => $request->catatan,
            'tanggal_bayar' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('warga.iuran')->with('success', 'Konfirmasi pembayaran berhasil dikirim! Menunggu validasi bendahara.');
    }

    /**
     * Halaman Pengaduan & Aspirasi Warga
     */
    public function complaints(): View
    {
        $complaints = Complaint::where('user_id', Auth::id())->latest()->paginate(10);

        return view('warga.pengaduan', compact('complaints'));
    }

    /**
     * Simpan Pengaduan Warga Baru
     */
    public function storeComplaint(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:infrastruktur,kebersihan,keamanan,kebisingan,lainnya'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'is_anonim' => ['nullable', 'boolean'],
        ]);

        Complaint::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'] ?? null,
            'is_anonim' => $request->boolean('is_anonim'),
            'status' => 'diterima',
        ]);

        return redirect()->route('warga.pengaduan')->with('success', 'Laporan pengaduan berhasil dikirim ke pengurus RT 15.');
    }
}
