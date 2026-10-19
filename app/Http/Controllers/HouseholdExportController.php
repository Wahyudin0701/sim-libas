<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use App\Models\RtSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class HouseholdExportController extends Controller
{
    /**
     * Bangun query Kartu Keluarga sesuai parameter pencarian dan filter jumlah anggota.
     */
    protected function getFilteredQuery(Request $request)
    {
        $searchTerm = $request->query('search');
        $anggotaFilter = $request->query('anggota');

        $query = Household::with(['kepalaKeluarga'])
            ->withCount('residents')
            ->latest('created_at');

        if (filled($searchTerm)) {
            $term = trim($searchTerm);
            $query->where(function ($q) use ($term) {
                $q->where('alamat', 'like', "%{$term}%")
                  ->orWhere('id_kk', 'like', "%{$term}%")
                  ->orWhere('no_kk_hash', hash('sha256', $term))
                  ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                      $k->where('nama', 'like', "%{$term}%")
                        ->orWhere('telepon', 'like', "%{$term}%")
                        ->orWhere('nik_hash', hash('sha256', $term));
                  });
            });
        }

        if (filled($anggotaFilter)) {
            if ($anggotaFilter === '6+') {
                $query->has('residents', '>=', 6);
            } elseif ($anggotaFilter === '0') {
                $query->doesntHave('residents');
            } else {
                $query->has('residents', '=', (int) $anggotaFilter);
            }
        }

        return $query;
    }

    /**
     * Pastikan pengguna memiliki hak akses pengurus RT / admin.
     */
    protected function checkAuthorization(): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (method_exists($user, 'hasAnyRole')) {
            if (!$user->hasAnyRole(['super_admin', 'ketua_rt', 'sekretaris', 'bendahara', 'admin'])) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengekspor data kependudukan.');
            }
        }
    }

    /**
     * Ekspor Data Kartu Keluarga ke PDF (Preview langsung di tab browser baru).
     */
    public function exportPdf(Request $request): Response
    {
        $this->checkAuthorization();

        $query = $this->getFilteredQuery($request);
        $households = $query->get();
        $setting = RtSetting::first();

        $searchTerm = $request->query('search');
        $anggotaFilter = $request->query('anggota');

        $filterInfo = 'Semua Data';
        if (filled($searchTerm) && filled($anggotaFilter)) {
            $anggotaText = match ($anggotaFilter) {
                '6+' => '6 Jiwa atau Lebih',
                '0' => '0 Jiwa (Kosong)',
                default => "{$anggotaFilter} Jiwa",
            };
            $filterInfo = "Pencarian: \"{$searchTerm}\" | Filter: {$anggotaText}";
        } elseif (filled($searchTerm)) {
            $filterInfo = "Pencarian: \"{$searchTerm}\"";
        } elseif (filled($anggotaFilter)) {
            $filterInfo = match ($anggotaFilter) {
                '6+' => '6 Jiwa atau Lebih',
                '0' => '0 Jiwa (Kosong)',
                default => "{$anggotaFilter} Jiwa",
            };
        }

        $totalKk = $households->count();
        $totalJiwa = $households->sum('residents_count');

        $logoPath = public_path('images/logo-icon.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('households.pdf', [
            'households' => $households,
            'setting' => $setting,
            'filterInfo' => $filterInfo,
            'totalKk' => $totalKk,
            'totalJiwa' => $totalJiwa,
            'printedAt' => now(),
            'user' => Auth::user(),
            'logoBase64' => $logoBase64,
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan_Kartu_Keluarga_RT15_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Ekspor Data Kartu Keluarga ke Excel (.xlsx) dengan dialog Save As / unduhan langsung.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $this->checkAuthorization();

        $query = $this->getFilteredQuery($request);

        $tempFile = tempnam(sys_get_temp_dir(), 'export_kk_') . '.xlsx';
        $writer = new Writer();
        $writer->openToFile($tempFile);

        // Header Style (Deep Navy background #0B3A85, white bold text)
        $headerStyle = (new Style())
            ->setFontBold()
            ->setFontSize(10)
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor('0B3A85');

        $headers = [
            'NO',
            'ID KK',
            'NOMOR KK',
            'KEPALA KELUARGA',
            'NIK KEPALA',
            'NO. TELEPON / WA',
            'ALAMAT RUMAH',
            'FOTO KK',
            'JUMLAH ANGGOTA',
        ];

        $writer->addRow(Row::fromValues($headers, $headerStyle));

        $dataStyle = (new Style())->setFontSize(10);
        $index = 1;

        foreach ($query->cursor() as $row) {
            $kepala = $row->kepalaKeluarga;
            $writer->addRow(Row::fromValues([
                $index++,
                $row->id_kk ?? '-',
                (string) ($row->no_kk ?? '-'),
                $kepala?->nama ?? '-',
                (string) ($kepala?->nik ?? '-'),
                $kepala?->telepon ?? '-',
                $row->alamat ?? '-',
                $row->foto_kk ? 'Ada' : 'Belum Ada',
                $row->residents_count . ' Jiwa',
            ], $dataStyle));
        }

        $writer->close();

        $filename = 'Laporan_Kartu_Keluarga_' . now()->format('Ymd_Hi') . '.xlsx';

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
