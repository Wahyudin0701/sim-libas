<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\CashTransaction;
use App\Models\Complaint;
use App\Models\Event;
use App\Models\FeeType;
use App\Models\GuestReport;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\MsmeProfile;
use App\Models\PatrolSchedule;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\RtSetting;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminExportController extends Controller
{
    /**
     * Pastikan pengguna memiliki peran pengurus RT / admin.
     */
    protected function checkAuthorization(): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (method_exists($user, 'hasAnyRole')) {
            if (!$user->hasAnyRole(['super_admin', 'ketua_rt', 'sekretaris', 'bendahara', 'admin'])) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengekspor data.');
            }
        }
    }

    /**
     * Dapatkan konfigurasi, header, dan data baris untuk resource yang diminta.
     */
    protected function getResourceConfig(string $resource, Request $request): array
    {
        $search = $request->query('search');
        $setting = RtSetting::first();

        switch ($resource) {
            case 'households':
                $anggotaFilter = $request->query('anggota');
                $query = Household::with('kepalaKeluarga')->withCount('residents')->latest('created_at');
                if (filled($search)) {
                    $term = trim($search);
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
                $filterInfo = 'Semua Data';
                if (filled($search) && filled($anggotaFilter)) {
                    $filterInfo = "Pencarian: \"{$search}\" | Filter: " . ($anggotaFilter === '6+' ? '6 Jiwa atau Lebih' : ($anggotaFilter === '0' ? '0 Jiwa (Kosong)' : "{$anggotaFilter} Jiwa"));
                } elseif (filled($search)) {
                    $filterInfo = "Pencarian: \"{$search}\"";
                } elseif (filled($anggotaFilter)) {
                    $filterInfo = $anggotaFilter === '6+' ? '6 Jiwa atau Lebih' : ($anggotaFilter === '0' ? '0 Jiwa (Kosong)' : "{$anggotaFilter} Jiwa");
                }

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $kepala = $item->kepalaKeluarga;
                    $rows[] = [
                        $index++,
                        $item->id_kk ?? '-',
                        (string) ($item->no_kk ?? '-'),
                        $kepala?->nama ?? '-',
                        (string) ($kepala?->nik ?? '-'),
                        $kepala?->telepon ?? '-',
                        $item->alamat ?? '-',
                        $item->foto_kk ? 'Ada' : 'Belum Ada',
                        $item->residents_count . ' Jiwa',
                    ];
                }

                return [
                    'title' => 'Laporan Data Kartu Keluarga (KK)',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Kependudukan & Kartu Keluarga',
                    'filename' => 'Laporan_Kartu_Keluarga_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'ID KK', 'NOMOR KK', 'KEPALA KELUARGA', 'NIK KEPALA', 'NO. TELEPON / WA', 'ALAMAT RUMAH', 'FOTO KK', 'JUMLAH ANGGOTA'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-left', 'text-center', 'text-center', 'text-left', 'text-center', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'residents':
                $statusFilter = $request->query('status');
                $genderFilter = $request->query('gender');
                $query = Resident::with('household')->latest('created_at');
                if (filled($search)) {
                    $term = trim($search);
                    $query->where(function ($q) use ($term) {
                        $q->where('nama', 'like', "%{$term}%")
                          ->orWhere('id_warga', 'like', "%{$term}%")
                          ->orWhere('telepon', 'like', "%{$term}%")
                          ->orWhere('pekerjaan', 'like', "%{$term}%")
                          ->orWhere('nik_hash', hash('sha256', $term))
                          ->orWhereHas('household', function ($h) use ($term) {
                              $h->where('id_kk', 'like', "%{$term}%")
                                ->orWhere('alamat', 'like', "%{$term}%");
                          });
                    });
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $query->where('status_kependudukan', $statusFilter);
                }
                if (filled($genderFilter) && $genderFilter !== 'semua') {
                    $query->where('jenis_kelamin', $genderFilter);
                }

                $filterParts = [];
                if (filled($search)) {
                    $filterParts[] = "Pencarian: \"{$search}\"";
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $filterParts[] = "Status: " . ucfirst($statusFilter);
                }
                if (filled($genderFilter) && $genderFilter !== 'semua') {
                    $filterParts[] = "Jenis Kelamin: " . ($genderFilter === 'L' ? 'Laki-laki' : 'Perempuan');
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Data';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        $item->id_warga ?? '-',
                        $item->household?->id_kk ?? '-',
                        $item->nama,
                        (string) ($item->nik ?? '-'),
                        $item->jenis_kelamin === 'L' ? 'L' : 'P',
                        ucwords(str_replace('_', ' ', $item->hubungan_keluarga ?? '-')),
                        ($item->tempat_lahir ?? '-') . ', ' . ($item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-'),
                        $item->pekerjaan ?? '-',
                        $item->telepon ?? '-',
                        $item->alamat ?? $item->household?->alamat ?? '-',
                        ucfirst($item->status_kependudukan ?? 'Aktif'),
                    ];
                }

                return [
                    'title' => 'Laporan Data Penduduk & Warga RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Kependudukan RT 15',
                    'filename' => 'Laporan_Data_Warga_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'ID WARGA', 'ID KK', 'NAMA LENGKAP', 'NIK', 'L/P', 'HUBUNGAN', 'TEMPAT, TGL LAHIR', 'PEKERJAAN', 'NO. TELEPON / WA', 'ALAMAT RUMAH', 'STATUS'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-left', 'text-center', 'text-center', 'text-left', 'text-left', 'text-left', 'text-center', 'text-left', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'events':
                $kategoriFilter = $request->query('kategori');
                $waktuFilter = $request->query('waktu');
                $query = Event::with('pembuat')->orderBy('tanggal_mulai', 'desc');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(lokasi) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(penyelenggara) LIKE ?', ["%{$term}%"]);
                    });
                }
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') {
                    $query->where('kategori', $kategoriFilter);
                }
                if ($waktuFilter === 'mendatang') {
                    $query->where('tanggal_mulai', '>=', now());
                } elseif ($waktuFilter === 'bulan_ini') {
                    $query->whereMonth('tanggal_mulai', now()->month)->whereYear('tanggal_mulai', now()->year);
                } elseif ($waktuFilter === 'lewat') {
                    $query->where('tanggal_mulai', '<', now());
                }

                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') $filterParts[] = "Kategori: " . ucwords(str_replace('_', ' ', $kategoriFilter));
                if (filled($waktuFilter) && $waktuFilter !== 'semua') $filterParts[] = "Waktu: " . ucfirst(str_replace('_', ' ', $waktuFilter));
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Agenda Kegiatan';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        $item->judul,
                        ucwords(str_replace('_', ' ', $item->kategori)),
                        $item->lokasi ?? '-',
                        $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('d/m/Y H:i') : '-',
                        $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-',
                        $item->is_recurring ? 'Ya' : 'Tidak',
                        $item->tanggal_mulai >= now() ? 'Mendatang' : 'Selesai',
                        $item->penyelenggara ?: ($item->pembuat?->name ?? 'Pengurus RT 15'),
                    ];
                }

                return [
                    'title' => 'Laporan Agenda Kegiatan Warga RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Komunikasi & Kegiatan Warga',
                    'filename' => 'Laporan_Agenda_Kegiatan_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'NAMA KEGIATAN', 'KATEGORI', 'LOKASI', 'WAKTU MULAI', 'WAKTU SELESAI', 'RUTIN', 'STATUS', 'PENYELENGGARA'],
                    'alignments' => ['text-center', 'text-left', 'text-left', 'text-left', 'text-center', 'text-center', 'text-center', 'text-center', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'announcements':
                $statusFilter = $request->query('status');
                $rawTarget = $request->query('target') ?: $request->query('kategori');
                $targetFilter = (is_string($rawTarget) && !str_contains($rawTarget, '(') && !str_contains($rawTarget, 'function')) ? trim($rawTarget) : '';
                $query = Announcement::with('pembuat')->latest('created_at');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(isi) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(target) LIKE ?', ["%{$term}%"]);
                    });
                }

                if ($statusFilter === 'tayang') {
                    $query->where('is_published', true);
                } elseif ($statusFilter === 'pinned') {
                    $query->where('is_pinned', true);
                } elseif ($statusFilter === 'draft') {
                    $query->where('is_published', false);
                }

                if (filled($targetFilter) && in_array($targetFilter, ['semua', 'pengurus', 'warga', 'tetap', 'kontrak'])) {
                    $query->where('target', $targetFilter);
                }

                $filterParts = [];
                if (filled($search)) {
                    $filterParts[] = "Pencarian: \"{$search}\"";
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $statusLabels = [
                        'tayang' => 'Sedang Tayang',
                        'pinned' => 'Disematkan (Pin)',
                        'draft' => 'Draft / Arsip',
                    ];
                    $filterParts[] = "Status: " . ($statusLabels[$statusFilter] ?? ucfirst($statusFilter));
                }
                if (filled($targetFilter) && in_array($targetFilter, ['semua', 'pengurus', 'warga', 'tetap', 'kontrak'])) {
                    $targetLabels = [
                        'semua' => 'Semua Warga',
                        'pengurus' => 'Khusus Pengurus',
                        'warga' => 'Warga Tetap & Kontrak',
                    ];
                    $filterParts[] = "Target: " . ($targetLabels[$targetFilter] ?? ucfirst($targetFilter));
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Pengumuman';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        $item->judul,
                        ucwords(str_replace('_', ' ', $item->target ?? 'semua')),
                        $item->tanggal_tayang ? Carbon::parse($item->tanggal_tayang)->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-'),
                        $item->tanggal_berakhir ? Carbon::parse($item->tanggal_berakhir)->format('d/m/Y') : 'Tanpa Batas',
                        $item->is_published ? 'Aktif Tayang' : 'Draft',
                        $item->pembuat?->name ?? 'Pengurus RT',
                    ];
                }

                return [
                    'title' => 'Laporan Pengumuman Resmi RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Komunikasi Warga RT 15',
                    'filename' => 'Laporan_Pengumuman_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'JUDUL PENGUMUMAN', 'TARGET AUDIENS', 'TANGGAL TAYANG', 'BERAKHIR', 'STATUS', 'DIBUAT OLEH'],
                    'alignments' => ['text-center', 'text-left', 'text-center', 'text-center', 'text-center', 'text-center', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'cash-transactions':
                $jenisFilter = $request->query('jenis');
                $kategoriFilter = $request->query('kategori');
                $periodeFilter = $request->query('periode');
                $startDate = $request->query('start_date');
                $endDate = $request->query('end_date');
                $query = CashTransaction::with('pencatat')->orderBy('tanggal', 'desc')->latest('id');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"]);
                    });
                }
                if (filled($jenisFilter) && $jenisFilter !== 'semua') {
                    $query->where('jenis', $jenisFilter);
                }
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') {
                    $query->where('kategori', $kategoriFilter);
                }
                if ($periodeFilter === 'bulan_ini') {
                    $query->whereMonth('tanggal', now()->month)
                          ->whereYear('tanggal', now()->year);
                } elseif (filled($startDate) || filled($endDate)) {
                    if (filled($startDate)) {
                        $query->where('tanggal', '>=', $startDate);
                    }
                    if (filled($endDate)) {
                        $query->where('tanggal', '<=', $endDate);
                    }
                }

                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($jenisFilter) && $jenisFilter !== 'semua') $filterParts[] = "Jenis: " . ucfirst($jenisFilter);
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') $filterParts[] = "Kategori: " . ucwords(str_replace('_', ' ', $kategoriFilter));
                if ($periodeFilter === 'bulan_ini') {
                    $filterParts[] = "Periode: Bulan Ini (" . now()->translatedFormat('F Y') . ")";
                } elseif (filled($startDate) && filled($endDate)) {
                    $filterParts[] = "Periode: " . Carbon::parse($startDate)->format('d/m/Y') . " - " . Carbon::parse($endDate)->format('d/m/Y');
                } elseif (filled($startDate)) {
                    $filterParts[] = "Mulai: " . Carbon::parse($startDate)->format('d/m/Y');
                } elseif (filled($endDate)) {
                    $filterParts[] = "Sampai: " . Carbon::parse($endDate)->format('d/m/Y');
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Transaksi Buku Kas (All Time)';

                $isFiltered = !empty($filterParts);

                $items = $query->get();

                // Hitung total pemasukan & pengeluaran terfilter
                $totalPemasukanFilter = (float) $items->where('jenis', 'masuk')->sum('jumlah');
                $totalPengeluaranFilter = (float) $items->where('jenis', 'keluar')->sum('jumlah');
                $saldoPeriodeFilter = $totalPemasukanFilter - $totalPengeluaranFilter;

                // Hitung saldo kas RT saat ini (kumulatif all-time)
                $totalPemasukanAllTime = (float) CashTransaction::where('jenis', 'masuk')->sum('jumlah');
                $totalPengeluaranAllTime = (float) CashTransaction::where('jenis', 'keluar')->sum('jumlah');
                $saldoKasSaatIni = $totalPemasukanAllTime - $totalPengeluaranAllTime;

                $rows = [];
                $index = 1;
                foreach ($items as $item) {
                    $rows[] = [
                        $index++,
                        $item->tanggal ? Carbon::parse($item->tanggal)->format('d/m/Y') : '-',
                        'KAS-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                        strtoupper($item->jenis),
                        ucwords(str_replace('_', ' ', $item->kategori ?? '-')),
                        $item->deskripsi ?? '-',
                        $item->jenis === 'masuk' ? 'Rp ' . number_format($item->jumlah, 0, ',', '.') : '-',
                        $item->jenis === 'keluar' ? 'Rp ' . number_format($item->jumlah, 0, ',', '.') : '-',
                        $item->pencatat?->name ?? 'Bendahara RT',
                    ];
                }

                $financialSummary = [
                    'isFiltered' => $isFiltered,
                    'totalPemasukan' => $totalPemasukanFilter,
                    'totalPengeluaran' => $totalPengeluaranFilter,
                    'saldoPeriode' => $saldoPeriodeFilter,
                    'saldoSaatIni' => $saldoKasSaatIni,
                ];

                $tableFooters = [
                    [
                        'bg' => '#F1F5F9',
                        'cells' => [
                            [
                                'colspan' => 6,
                                'text' => 'TOTAL' . ($isFiltered ? ' (SESUAI KRITERIA / FILTER):' : ' KESELURUHAN (ALL TIME):'),
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => '#0F1F3D',
                            ],
                            [
                                'colspan' => 1,
                                'text' => 'Rp ' . number_format($totalPemasukanFilter, 0, ',', '.'),
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => '#047857',
                            ],
                            [
                                'colspan' => 1,
                                'text' => 'Rp ' . number_format($totalPengeluaranFilter, 0, ',', '.'),
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => '#B91C1C',
                            ],
                            [
                                'colspan' => 1,
                                'text' => '-',
                                'align' => 'text-center',
                                'bold' => false,
                                'color' => '#94A3B8',
                            ],
                        ],
                    ],
                    [
                        'bg' => '#EBF3FC',
                        'cells' => [
                            [
                                'colspan' => 6,
                                'text' => ($isFiltered ? 'SALDO ARUS KAS (PERIODE FILTER):' : 'SALDO AKHIR BUKU KAS:'),
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => '#0B3A85',
                            ],
                            [
                                'colspan' => 2,
                                'text' => 'Rp ' . number_format($saldoPeriodeFilter, 0, ',', '.'),
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => ($saldoPeriodeFilter >= 0 ? '#047857' : '#B91C1C'),
                            ],
                            [
                                'colspan' => 1,
                                'text' => ($saldoPeriodeFilter >= 0 ? 'Surplus' : 'Defisit'),
                                'align' => 'text-center',
                                'bold' => true,
                                'color' => '#0B3A85',
                            ],
                        ],
                    ],
                    [
                        'bg' => '#ECFDF5',
                        'cells' => [
                            [
                                'colspan' => 6,
                                'text' => 'TOTAL SALDO KAS RT SAAT INI (KUMULATIF ALL-TIME):',
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => '#065F46',
                            ],
                            [
                                'colspan' => 2,
                                'text' => 'Rp ' . number_format($saldoKasSaatIni, 0, ',', '.'),
                                'align' => 'text-right',
                                'bold' => true,
                                'color' => '#065F46',
                            ],
                            [
                                'colspan' => 1,
                                'text' => 'Kas Fisik RT',
                                'align' => 'text-center',
                                'bold' => true,
                                'color' => '#047857',
                            ],
                        ],
                    ],
                ];

                $excelSummaryRows = [
                    ['', '', '', '', '', 'TOTAL' . ($isFiltered ? ' (SESUAI FILTER)' : ' KESELURUHAN'), 'Rp ' . number_format($totalPemasukanFilter, 0, ',', '.'), 'Rp ' . number_format($totalPengeluaranFilter, 0, ',', '.'), ''],
                    ['', '', '', '', '', ($isFiltered ? 'SALDO ARUS KAS (PERIODE FILTER)' : 'SALDO AKHIR KAS'), 'Rp ' . number_format($saldoPeriodeFilter, 0, ',', '.'), '', ($saldoPeriodeFilter >= 0 ? 'Surplus' : 'Defisit')],
                    ['', '', '', '', '', 'TOTAL SALDO KAS RT SAAT INI (KUMULATIF ALL-TIME)', 'Rp ' . number_format($saldoKasSaatIni, 0, ',', '.'), '', 'Kas Fisik RT'],
                ];

                return [
                    'title' => 'Laporan Buku Kas & Arus Keuangan RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Keuangan & Pembukuan Kas RT 15',
                    'filename' => 'Laporan_Buku_Kas_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'financialSummary' => $financialSummary,
                    'tableFooters' => $tableFooters,
                    'excelSummaryRows' => $excelSummaryRows,
                    'headers' => ['NO', 'TANGGAL', 'KODE TRANSAKSI', 'JENIS', 'KATEGORI', 'KETERANGAN', 'PEMASUKAN', 'PENGELUARAN', 'DICATAT OLEH'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-center', 'text-left', 'text-left', 'text-right', 'text-right', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'complaints':
                $statusFilter = $request->query('status');
                $kategoriFilter = $request->query('kategori');
                $query = Complaint::with(['pelapor', 'penanggungJawab'])->latest('created_at');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(judul) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"]);
                    });
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $query->where('status', $statusFilter);
                }
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') {
                    $query->where('kategori', $kategoriFilter);
                }

                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $statusLabels = [
                        'diterima' => 'Diterima',
                        'diproses' => 'Sedang Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ];
                    $filterParts[] = "Status: " . ($statusLabels[$statusFilter] ?? ucfirst($statusFilter));
                }
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') {
                    $filterParts[] = "Kategori: " . ucwords(str_replace('_', ' ', $kategoriFilter));
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Laporan Pengaduan';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $pelaporNama = $item->is_anonim ? 'Anonim (Warga RT 15)' : ($item->pelapor?->name ?? 'Warga RT 15');
                    $rows[] = [
                        $index++,
                        'PGD-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                        $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
                        $pelaporNama,
                        ucwords(str_replace('_', ' ', $item->kategori ?? 'umum')),
                        $item->judul,
                        strtoupper($item->status ?? 'menunggu'),
                        $item->penanggungJawab?->name ?? 'Belum Ditugaskan',
                    ];
                }

                return [
                    'title' => 'Laporan Pengaduan & Aspirasi Warga RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Layanan Pengaduan Warga RT 15',
                    'filename' => 'Laporan_Pengaduan_Warga_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'KODE TIKET', 'TANGGAL', 'PELAPOR', 'KATEGORI', 'JUDUL PENGADUAN', 'STATUS', 'PETUGAS PENANGAN'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-left', 'text-left', 'text-left', 'text-center', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'fee-types':
                $query = FeeType::orderBy('nama');
                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"]);
                    });
                }
                $filterInfo = filled($search) ? "Pencarian: \"{$search}\"" : 'Semua Jenis Iuran';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        'IUR-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                        $item->nama,
                        ucfirst($item->periode ?? 'bulanan'),
                        'Rp ' . number_format($item->nominal ?? 0, 0, ',', '.'),
                        $item->deskripsi ?? '-',
                        $item->is_active ? 'Aktif' : 'Non-Aktif',
                    ];
                }

                return [
                    'title' => 'Laporan Master Jenis Iuran RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Keuangan RT 15',
                    'filename' => 'Laporan_Master_Jenis_Iuran_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'KODE', 'NAMA IURAN', 'PERIODE', 'NOMINAL (RP)', 'DESKRIPSI', 'STATUS'],
                    'alignments' => ['text-center', 'text-center', 'text-left', 'text-center', 'text-right', 'text-left', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'guest-reports':
                $statusFilter = $request->query('status');
                $query = GuestReport::with(['household.kepalaKeluarga', 'pelapor'])->latest('tanggal_datang');
                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(nama_tamu) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(nik_tamu) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(asal) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(tujuan) LIKE ?', ["%{$term}%"]);
                    });
                }
                if ($statusFilter === 'menginap') {
                    $query->whereNull('tanggal_pulang');
                } elseif ($statusFilter === 'pulang') {
                    $query->whereNotNull('tanggal_pulang');
                }

                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if ($statusFilter === 'menginap') $filterParts[] = "Status: Masih Menginap";
                if ($statusFilter === 'pulang') $filterParts[] = "Status: Sudah Pulang";
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Laporan Tamu';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $tuanRumah = $item->household?->kepalaKeluarga?->nama ?? ($item->pelapor?->name ?? 'Warga RT 15');
                    $rows[] = [
                        $index++,
                        $item->nama_tamu,
                        (string) ($item->nik_tamu ?? '-'),
                        $item->asal ?? '-',
                        $tuanRumah,
                        $item->tujuan ?? '-',
                        $item->tanggal_datang ? Carbon::parse($item->tanggal_datang)->format('d/m/Y') : '-',
                        $item->tanggal_pulang ? Carbon::parse($item->tanggal_pulang)->format('d/m/Y') : 'Masih Menginap',
                    ];
                }

                return [
                    'title' => 'Laporan Tamu & Kunjungan RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Keamanan & Ketertiban RT 15',
                    'filename' => 'Laporan_Tamu_Kunjungan_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'NAMA TAMU', 'NIK / IDENTITAS', 'ASAL TAMU', 'TUAN RUMAH / PENERIMA', 'TUJUAN KUNJUNGAN', 'TGL DATANG', 'TGL PULANG'],
                    'alignments' => ['text-center', 'text-left', 'text-center', 'text-left', 'text-left', 'text-left', 'text-center', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'invoices':
                $statusFilter = $request->query('status');
                $feeTypeId = $request->query('fee_type_id') ?: $request->query('fee_type');
                $query = Invoice::with(['household.kepalaKeluarga', 'feeType'])->latest('periode_tahun')->latest('periode_bulan');
                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereHas('household.kepalaKeluarga', function ($k) use ($term) {
                            $k->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                        })
                        ->orWhereHas('household', function ($h) use ($term) {
                            $h->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"]);
                        });
                    });
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $query->where('status', $statusFilter);
                }
                if (filled($feeTypeId) && $feeTypeId !== 'semua') {
                    $query->where('fee_type_id', $feeTypeId);
                }
                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $statusLabels = [
                        'belum_bayar' => 'Belum Bayar',
                        'sebagian' => 'Sebagian',
                        'lunas' => 'Lunas',
                    ];
                    $filterParts[] = "Status: " . ($statusLabels[$statusFilter] ?? ucfirst(str_replace('_', ' ', $statusFilter)));
                }
                if (filled($feeTypeId) && $feeTypeId !== 'semua') {
                    $feeType = FeeType::find($feeTypeId);
                    if ($feeType) $filterParts[] = "Jenis: {$feeType->nama}";
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Tagihan Iuran';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $bulanNama = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    $periode = ($bulanNama[$item->periode_bulan] ?? $item->periode_bulan) . ' ' . $item->periode_tahun;
                    $rows[] = [
                        $index++,
                        'INV-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                        $periode,
                        $item->feeType?->nama ?? 'Iuran RT',
                        $item->household?->kepalaKeluarga?->nama ?? ($item->household?->alamat ?? 'Warga'),
                        'Rp ' . number_format($item->jumlah ?? 0, 0, ',', '.'),
                        strtoupper($item->status ?? 'belum_bayar'),
                        $item->tanggal_jatuh_tempo ? Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') : '-',
                    ];
                }

                return [
                    'title' => 'Laporan Tagihan Iuran Warga RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Keuangan RT 15',
                    'filename' => 'Laporan_Tagihan_Iuran_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'KODE INVOICE', 'PERIODE', 'JENIS IURAN', 'KELUARGA / KK', 'JUMLAH (RP)', 'STATUS', 'JATUH TEMPO'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-left', 'text-left', 'text-right', 'text-center', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'letter-types':
                $query = LetterType::orderBy('urutan');
                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(kode) LIKE ?', ["%{$term}%"]);
                    });
                }
                $filterInfo = filled($search) ? "Pencarian: \"{$search}\"" : 'Semua Master Jenis Surat';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $syaratText = is_array($item->persyaratan) ? implode(', ', $item->persyaratan) : ($item->persyaratan ?? '-');
                    $rows[] = [
                        $index++,
                        $item->kode ?? ('SRT-' . $item->id),
                        $item->nama,
                        $item->format_nomor ?? '-',
                        $syaratText ?: '-',
                        $item->is_active ? 'Aktif' : 'Non-Aktif',
                    ];
                }

                return [
                    'title' => 'Laporan Master Jenis Surat Pengantar',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Administrasi Layanan Surat RT 15',
                    'filename' => 'Laporan_Master_Jenis_Surat_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'KODE SURAT', 'NAMA JENIS SURAT', 'FORMAT NOMOR', 'PERSYARATAN DOKUMEN', 'STATUS'],
                    'alignments' => ['text-center', 'text-center', 'text-left', 'text-left', 'text-left', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'letters':
                $statusFilter = $request->query('status');
                $letterTypeId = $request->query('letter_type_id') ?: $request->query('type');
                $query = Letter::with(['resident', 'letterType', 'approver'])->latest('created_at');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(nomor_surat) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(keperluan) LIKE ?', ["%{$term}%"])
                          ->orWhereHas('resident', function ($r) use ($term) {
                              $r->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                          });
                    });
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $query->where('status', $statusFilter);
                }
                if (filled($letterTypeId) && $letterTypeId !== 'semua') {
                    $query->where('letter_type_id', $letterTypeId);
                }
                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $statusLabels = [
                        'diajukan' => 'Menunggu Persetujuan',
                        'diperiksa' => 'Sedang Diperiksa',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ];
                    $filterParts[] = "Status: " . ($statusLabels[$statusFilter] ?? ucfirst($statusFilter));
                }
                if (filled($letterTypeId) && $letterTypeId !== 'semua') {
                    $lType = LetterType::find($letterTypeId);
                    if ($lType) $filterParts[] = "Jenis: {$lType->nama}";
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Pelayanan Surat';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        $item->nomor_surat ?: '-',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                        $item->resident?->nama ?? 'Warga',
                        (string) ($item->resident?->nik ?? '-'),
                        $item->letterType?->nama ?? 'Surat Pengantar',
                        $item->keperluan ?? '-',
                        strtoupper($item->status ?? 'diajukan'),
                        $item->approver?->name ?? ($item->status === 'disetujui' ? 'Ketua RT 15' : '-'),
                    ];
                }

                return [
                    'title' => 'Laporan Pelayanan Surat Pengantar RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Administrasi Layanan Surat RT 15',
                    'filename' => 'Laporan_Surat_Pengantar_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'NOMOR SURAT', 'TANGGAL', 'NAMA WARGA', 'NIK', 'JENIS SURAT', 'KEPERLUAN', 'STATUS', 'DISETUJUI OLEH'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-left', 'text-center', 'text-left', 'text-left', 'text-center', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'msme-profiles':
                $kategoriFilter = $request->query('kategori');
                $statusFilter = $request->query('status');
                $query = MsmeProfile::with('household.kepalaKeluarga')->latest('created_at');
                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(nama_usaha) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$term}%"]);
                    });
                }
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') {
                    $query->where('kategori', $kategoriFilter);
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $query->where('status_moderasi', $statusFilter);
                }
                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($kategoriFilter) && $kategoriFilter !== 'semua') $filterParts[] = "Kategori: " . ucwords(str_replace('_', ' ', $kategoriFilter));
                if (filled($statusFilter) && $statusFilter !== 'semua') $filterParts[] = "Status: " . ucfirst($statusFilter);
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Katalog UMKM';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        $item->nama_usaha,
                        $item->household?->kepalaKeluarga?->nama ?? 'Warga RT 15',
                        ucwords(str_replace('_', ' ', $item->kategori ?? 'kuliner')),
                        $item->telepon ?? ($item->whatsapp ?? '-'),
                        $item->alamat ?? ($item->household?->alamat ?? '-'),
                        ucfirst($item->status_moderasi ?? 'approved'),
                    ];
                }

                return [
                    'title' => 'Laporan Direktori UMKM Warga RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Pemberdayaan Ekonomi & UMKM',
                    'filename' => 'Laporan_Direktori_UMKM_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'NAMA USAHA', 'PEMILIK (KK)', 'KATEGORI', 'NO. KONTAK / WA', 'ALAMAT USAHA', 'STATUS MODERASI'],
                    'alignments' => ['text-center', 'text-left', 'text-left', 'text-left', 'text-center', 'text-left', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'patrol-schedules':
                $reguFilter = $request->query('regu');
                $query = PatrolSchedule::with('members.resident')->orderBy('hari_ke', 'asc');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(regu) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(pos_lokasi) LIKE ?', ["%{$term}%"])
                          ->orWhereHas('residents', function ($rq) use ($term) {
                              $rq->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                          });
                    });
                }
                if (filled($reguFilter) && $reguFilter !== 'semua') {
                    $query->where('regu', $reguFilter);
                }
                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($reguFilter) && $reguFilter !== 'semua') $filterParts[] = "Regu: {$reguFilter}";
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Master Jadwal Ronda 7 Hari (Senin - Minggu)';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $personil = $item->members->map(fn ($m) => ($m->peran === 'ketua_regu' ? '⭐ ' : '') . ($m->resident?->nama ?? 'Warga'))->implode(', ');
                    $jam = ($item->waktu_mulai && $item->waktu_selesai) ? "{$item->waktu_mulai} - {$item->waktu_selesai} WIB" : '-';
                    $posCatatan = $item->pos_lokasi . ($item->catatan ? ' - ' . $item->catatan : '');
                    $rows[] = [
                        $index++,
                        $item->nama_hari,
                        $item->regu ?? 'Regu Ronda',
                        $jam,
                        $item->members->count() . ' Orang',
                        !empty($personil) ? $personil : 'Belum ditentukan',
                        $posCatatan,
                    ];
                }

                return [
                    'title' => 'Master Jadwal Ronda Mingguan RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Keamanan & Ketertiban RT 15',
                    'filename' => 'Master_Jadwal_Ronda_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'HARI', 'REGU RONDA', 'JAM RONDA', 'JUMLAH PERSONIL', 'DAFTAR ANGGOTA BERTUGAS', 'POS / CATATAN'],
                    'alignments' => ['text-center', 'text-center', 'text-left', 'text-center', 'text-center', 'text-left', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'payments':
                $statusFilter = $request->query('status');
                $metodeFilter = $request->query('metode');
                $query = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType', 'validator'])->latest('tanggal_bayar');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"])
                          ->orWhere('jumlah', 'like', "%{$term}%")
                          ->orWhereRaw('LOWER(metode) LIKE ?', ["%{$term}%"])
                          ->orWhereHas('invoice.household', function ($h) use ($term) {
                              $h->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"])
                                ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                                    $k->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                                });
                          })
                          ->orWhereHas('invoice.feeType', function ($f) use ($term) {
                              $f->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                          });
                    });
                }
                if (filled($statusFilter) && $statusFilter !== 'semua') {
                    $query->where('status', $statusFilter);
                }
                if (filled($metodeFilter) && $metodeFilter !== 'semua') {
                    $query->where('metode', $metodeFilter);
                }
                $filterInfo = filled($search) ? "Pencarian: \"{$search}\"" : 'Semua Riwayat Pembayaran';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $noKk = (string) ($item->invoice?->household?->no_kk ?? '-');
                    $rows[] = [
                        $index++,
                        'BYR-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                        $item->tanggal_bayar ? Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') : '-',
                        $noKk,
                        $item->invoice?->household?->kepalaKeluarga?->nama ?? 'Warga',
                        $item->invoice?->feeType?->nama ?? 'Iuran RT',
                        'Rp ' . number_format($item->jumlah ?? 0, 0, ',', '.'),
                        strtoupper($item->metode ?? 'transfer'),
                        strtoupper($item->status ?? 'pending'),
                        $item->validator?->name ?? ($item->status === 'valid' ? 'Pengurus RT' : '-'),
                    ];
                }

                return [
                    'title' => 'Laporan Riwayat Pembayaran Iuran RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Keuangan RT 15',
                    'filename' => 'Laporan_Pembayaran_Iuran_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'KODE BAYAR', 'TGL BAYAR', 'NOMOR KK', 'KEPALA KELUARGA', 'JENIS IURAN', 'JUMLAH (RP)', 'METODE', 'STATUS', 'VALIDATOR'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-center', 'text-left', 'text-left', 'text-right', 'text-center', 'text-center', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'waste-deposits':
                $typeFilter = $request->query('type');
                $query = WasteDeposit::with(['household.headOfHousehold', 'wasteType', 'pencatat'])->latest('created_at');

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereHas('household.headOfHousehold', function ($q2) use ($term) {
                            $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                        })
                        ->orWhereHas('household', function ($q2) use ($term) {
                            $q2->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"]);
                        })
                        ->orWhereHas('wasteType', function ($q2) use ($term) {
                            $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                        })
                        ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"]);
                    });
                }
                if (filled($typeFilter) && $typeFilter !== 'semua') {
                    $query->where('waste_type_id', $typeFilter);
                }
                $filterParts = [];
                if (filled($search)) $filterParts[] = "Pencarian: \"{$search}\"";
                if (filled($typeFilter) && $typeFilter !== 'semua') {
                    $wType = WasteType::find($typeFilter);
                    if ($wType) $filterParts[] = "Jenis: {$wType->nama}";
                }
                $filterInfo = !empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Setoran Bank Sampah';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $noKk = (string) ($item->household?->no_kk ?? '-');
                    $rows[] = [
                        $index++,
                        $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
                        $noKk,
                        $item->household?->headOfHousehold?->nama ?? 'Warga RT 15',
                        $item->wasteType?->nama ?? 'Sampah Anorganik',
                        ucwords(str_replace('_', ' ', $item->wasteType?->kategori ?? 'anorganik')),
                        number_format($item->berat_kg ?? 0, 2, ',', '.') . ' ' . ($item->wasteType?->satuan ?? 'kg'),
                        'Rp ' . number_format($item->harga_saat_setor ?? 0, 0, ',', '.'),
                        'Rp ' . number_format($item->nilai ?? 0, 0, ',', '.'),
                        $item->pencatat?->name ?? 'Petugas Bank Sampah',
                    ];
                }

                return [
                    'title' => 'Laporan Setoran Bank Sampah RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Bank Sampah & Lingkungan Hidup RT 15',
                    'filename' => 'Laporan_Setoran_Bank_Sampah_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'TANGGAL SETOR', 'NOMOR KK', 'KEPALA KELUARGA', 'KOMODITAS SAMPAH', 'KATEGORI', 'BERAT / VOLUME', 'HARGA / SATUAN', 'NILAI TABUNGAN', 'PETUGAS PENCATAT'],
                    'alignments' => ['text-center', 'text-center', 'text-center', 'text-left', 'text-left', 'text-left', 'text-right', 'text-right', 'text-right', 'text-left'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            case 'waste-types':
                $kategoriFilter = $request->query('kategori');
                $query = WasteType::withCount('deposits')->withSum('deposits', 'berat_kg')->latest();

                if (filled($search)) {
                    $term = strtolower(trim($search));
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(satuan) LIKE ?', ["%{$term}%"]);
                    });
                }
                if (filled($kategoriFilter) && in_array($kategoriFilter, ['organik', 'anorganik', 'b3', 'lainnya'])) {
                    $query->where('kategori', $kategoriFilter);
                }
                $filterInfo = filled($search) ? "Pencarian: \"{$search}\"" : 'Semua Master Harga Sampah';

                $rows = [];
                $index = 1;
                foreach ($query->get() as $item) {
                    $rows[] = [
                        $index++,
                        $item->nama,
                        ucwords(str_replace('_', ' ', $item->kategori ?? 'anorganik')),
                        'Rp ' . number_format($item->harga_per_kg ?? 0, 0, ',', '.'),
                        strtoupper($item->satuan ?? 'KG'),
                        number_format($item->deposits_count ?? 0, 0, ',', '.') . ' Kali',
                        number_format($item->deposits_sum_berat_kg ?? 0, 2, ',', '.') . ' ' . ($item->satuan ?? 'kg'),
                        $item->is_active ? 'Menerima' : 'Ditutup',
                    ];
                }

                return [
                    'title' => 'Laporan Master Komoditas & Harga Bank Sampah RT 15',
                    'subtitle' => 'Wilayah Rukun Tetangga 15 / Rukun Warga 04',
                    'category' => 'Bank Sampah & Lingkungan Hidup RT 15',
                    'filename' => 'Laporan_Master_Sampah_' . now()->format('Ymd_His'),
                    'filterInfo' => $filterInfo,
                    'headers' => ['NO', 'NAMA KOMODITAS', 'KATEGORI', 'HARGA BELI', 'SATUAN', 'TOTAL TRANSAKSI', 'TOTAL TERKUMPUL', 'STATUS'],
                    'alignments' => ['text-center', 'text-left', 'text-left', 'text-right', 'text-center', 'text-right', 'text-right', 'text-center'],
                    'rows' => $rows,
                    'orientation' => 'landscape',
                ];

            default:
                abort(404, "Katalog data ekspor untuk resource '{$resource}' tidak ditemukan.");
        }
    }

    /**
     * Ekspor ke PDF (Pratinjau langsung di tab peramban baru).
     */
    public function exportPdf(string $resource, Request $request): Response
    {
        $this->checkAuthorization();

        $config = $this->getResourceConfig($resource, $request);
        $setting = RtSetting::first();

        $logoPath = public_path('images/logo-icon.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('exports.generic-pdf', [
            'title' => $config['title'],
            'subtitle' => $config['subtitle'],
            'category' => $config['category'],
            'filterInfo' => $config['filterInfo'],
            'financialSummary' => $config['financialSummary'] ?? null,
            'tableFooters' => $config['tableFooters'] ?? null,
            'headers' => $config['headers'],
            'cellAlignments' => $config['alignments'] ?? [],
            'rows' => $config['rows'],
            'setting' => $setting,
            'printedAt' => now(),
            'user' => Auth::user(),
            'logoBase64' => $logoBase64,
            'orientation' => $config['orientation'] ?? 'landscape',
        ])->setPaper('a4', $config['orientation'] ?? 'landscape');

        $filename = $config['filename'] . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Ekspor ke Excel (.xlsx) dengan unduhan langsung / Save As.
     */
    public function exportExcel(string $resource, Request $request): BinaryFileResponse
    {
        $this->checkAuthorization();

        $config = $this->getResourceConfig($resource, $request);

        $tempFile = tempnam(sys_get_temp_dir(), 'export_' . $resource . '_') . '.xlsx';
        $writer = new Writer();
        $writer->openToFile($tempFile);

        // Header Style (Deep Navy background #0B3A85, white bold text)
        $headerStyle = (new Style())
            ->setFontBold()
            ->setFontSize(10)
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor('0B3A85');

        $writer->addRow(Row::fromValues($config['headers'], $headerStyle));

        $dataStyle = (new Style())->setFontSize(10);

        foreach ($config['rows'] as $row) {
            // Pastikan setiap cell berupa string / number yang aman untuk Excel
            $writer->addRow(Row::fromValues(array_map(fn ($val) => is_numeric($val) && strlen((string)$val) > 11 ? (string)$val : $val, $row), $dataStyle));
        }

        if (!empty($config['excelSummaryRows'])) {
            $summaryStyle = (new Style())->setFontBold()->setFontSize(10)->setBackgroundColor('F1F5F9');
            foreach ($config['excelSummaryRows'] as $sRow) {
                $writer->addRow(Row::fromValues($sRow, $summaryStyle));
            }
        }

        $writer->close();

        $filename = $config['filename'] . '.xlsx';

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
