<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Complaint;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\GuestReport;
use App\Models\Household;
use App\Models\PatrolMember;
use App\Models\PatrolSchedule;
use App\Models\Resident;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommunicationAndSecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi data pengumuman, pengaduan, agenda kegiatan, lapor tamu, dan jadwal ronda RT 15
     */
    public function run(): void
    {
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first()
            ?? User::first();
        $sekretaris = User::where('email', 'sekretaris@simlibas.com')->first()
            ?? $ketuaRt;
        $wargaUser = User::where('email', 'like', '%@warga.simlibas.com')->first()
            ?? User::where('email', 'warga.dedi@simlibas.com')->first()
            ?? $ketuaRt;
        $wargaUsers = User::where('email', 'like', '%warga%')->take(6)->get();

        $households = Household::all();
        $residents = Resident::all();

        // 1. Pengumuman (6 data)
        $announcements = [
            [
                'judul' => 'Kerja Bakti Akbar Lingkungan RT 15 Sambut Musim Hujan',
                'isi' => "Diberitahukan kepada seluruh warga RT 15 bahwa pada hari Minggu mendatang akan diadakan kerja bakti bersama untuk membersihkan selokan utama dan memangkas dahan pohon yang membahayakan kabel listrik. Dimohon partisipasi perwakilan setiap rumah tangga.",
                'target' => 'semua',
                'is_pinned' => true,
                'is_published' => true,
                'tanggal_tayang' => Carbon::now()->subDays(2),
                'tanggal_berakhir' => Carbon::now()->addDays(14),
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Jadwal Fogging Nyamuk DBD Serentak Seluruh Blok RT 15',
                'isi' => "Pengasapan (fogging) nyamuk demam berdarah akan dilaksanakan serentak mulai pukul 07.00 WIB. Diharapkan warga menutup makanan dan membiarkan jendela terbuka agar asap dapat menjangkau sudut ruangan.",
                'target' => 'semua',
                'is_pinned' => false,
                'is_published' => true,
                'tanggal_tayang' => Carbon::now()->subDays(5),
                'tanggal_berakhir' => Carbon::now()->addDays(7),
                'dibuat_oleh' => $sekretaris->id,
            ],
            [
                'judul' => 'Sosialisasi Digitalisasi Layanan Warga SIM-LiBas RT 15',
                'isi' => "Kini pengurusan surat pengantar, pembayaran iuran kas RT, dan pengaduan fasilitas dapat diakses secara online melalui portal mandiri warga SIM-LiBas RT 15.",
                'target' => 'semua',
                'is_pinned' => true,
                'is_published' => true,
                'tanggal_tayang' => Carbon::now()->subDays(10),
                'tanggal_berakhir' => Carbon::now()->addDays(30),
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Himbauan Waspada Keamanan Lingkungan & Kunci Ganda Kendaraan',
                'isi' => "Mengingat meningkatnya mobilitas warga menjelang akhir tahun, dihimbau kepada seluruh warga untuk memastikan kendaraan terkunci ganda dan portal lingkungan ditutup mulai pukul 23.00 WIB.",
                'target' => 'semua',
                'is_pinned' => false,
                'is_published' => true,
                'tanggal_tayang' => Carbon::now()->subDays(1),
                'tanggal_berakhir' => Carbon::now()->addDays(20),
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Jadwal Pemeriksaan Kesehatan Gratis Lansia & Balita di Posyandu RT 15',
                'isi' => "Kegiatan penimbangan balita, imunisasi dasar, serta cek tekanan darah dan gula darah gratis bagi lansia akan dilaksanakan hari Sabtu depan di Balai Posyandu RT 15.",
                'target' => 'semua',
                'is_pinned' => false,
                'is_published' => true,
                'tanggal_tayang' => Carbon::now()->subDays(3),
                'tanggal_berakhir' => Carbon::now()->addDays(10),
                'dibuat_oleh' => $sekretaris->id,
            ],
            [
                'judul' => 'Program Kampung Bersih: Pilah Sampah Berhadiah Saldo Tabungan',
                'isi' => "Bank Sampah Pinang Masak Lestari RT 15 mengadakan promo setoran sampah daur ulang berhadiah ekstra tabungan selama bulan Oktober. Mari pilah sampah dari dapur rumah kita!",
                'target' => 'semua',
                'is_pinned' => false,
                'is_published' => true,
                'tanggal_tayang' => Carbon::now()->subDays(4),
                'tanggal_berakhir' => Carbon::now()->addDays(25),
                'dibuat_oleh' => $ketuaRt->id,
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::firstOrCreate(['judul' => $a['judul']], $a);
        }

        // 2. Pengaduan & Aspirasi Warga (6 data)
        $complaints = [
            [
                'user_id' => $wargaUser->id,
                'kategori' => 'infrastruktur',
                'judul' => 'Lampu Penerangan Jalan Gang Kenanga Mati',
                'deskripsi' => 'Lampu tiang di dekat pos kamling Kenanga nomor tiang 04 padam sejak 3 malam lalu, kondisi gang cukup gelap saat malam.',
                'lokasi' => 'Jl. Kenanga RT 15 Blok A dekat Tiang No. 04',
                'is_anonim' => false,
                'status' => 'diproses',
                'catatan_penanganan' => 'Sedang dijadwalkan penggantian bohlam LED baru oleh pengurus bidang perlengkapan.',
                'penanggung_jawab_id' => $ketuaRt->id,
            ],
            [
                'user_id' => $wargaUser->id,
                'kategori' => 'kebersihan',
                'judul' => 'Tumpukan Sampah Dahan Dekat Lahan Kosong Blok B',
                'deskripsi' => 'Ada sisa potongan dahan dan sampah daun yang menumpuk di dekat pagar lahan kosong Blok B dan mengganggu pejalan kaki.',
                'lokasi' => 'Blok B Belakang RT 15',
                'is_anonim' => false,
                'status' => 'selesai',
                'catatan_penanganan' => 'Telah diangkut oleh petugas kebersihan gerobak sampah pada hari Kamis pagi.',
                'penanggung_jawab_id' => $ketuaRt->id,
                'tanggal_selesai' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => $wargaUser->id,
                'kategori' => 'kebersihan',
                'judul' => 'Saluran Air Drainase Tersumbat Pasir dan Sampah Botol',
                'deskripsi' => 'Ketika hujan air mulai meluap ke badan jalan karena selokan tersumbat endapan pasir dan sampah plastik.',
                'lokasi' => 'Depan Rumah Blok C No. 12 RT 15',
                'is_anonim' => false,
                'status' => 'diterima',
                'catatan_penanganan' => null,
            ],
            [
                'user_id' => $wargaUser->id,
                'kategori' => 'keamanan',
                'judul' => 'Suara Musik Keras Melebihi Pukul 23.00 di Rumah Kost Gang 2',
                'deskripsi' => 'Ada aktivitas musik bervolume tinggi di salah satu rumah kost hingga larut malam yang mengganggu waktu istirahat balita dan lansia.',
                'lokasi' => 'Gang Melati 2 Blok B RT 15',
                'is_anonim' => true,
                'status' => 'selesai',
                'catatan_penanganan' => 'Petugas keamanan dan ketua regu ronda telah menegur penghuni kost dan disepakati batas jam tenang pukul 22.00.',
                'penanggung_jawab_id' => $ketuaRt->id,
                'tanggal_selesai' => Carbon::now()->subHours(10),
            ],
            [
                'user_id' => $wargaUser->id,
                'kategori' => 'infrastruktur',
                'judul' => 'Usulan Pemasangan Cermin Cembung di Tikungan Blok A',
                'deskripsi' => 'Tikungan masuk dari jalan utama menuju Blok A memiliki blindspot yang tajam, rawan terjadi senggolan sepeda motor.',
                'lokasi' => 'Simpang Tikungan Blok A RT 15',
                'is_anonim' => false,
                'status' => 'diterima',
                'catatan_penanganan' => 'Akan dibawa ke forum Rapat Koordinasi RT pekan depan untuk dianggarkan dalam kas fisik.',
                'penanggung_jawab_id' => $ketuaRt->id,
            ],
            [
                'user_id' => $wargaUser->id,
                'kategori' => 'keamanan',
                'judul' => 'Dahan Pohon Ketapang Menutupi Kabel Listrik PLN Dekat Pos Ronda',
                'deskripsi' => 'Dahan pohon ketapang yang sudah rimbun bergesekan dengan bentangan kabel listrik utama saat angin kencang.',
                'lokasi' => 'Dekat Pos Kamling Utama RT 15',
                'is_anonim' => false,
                'status' => 'diproses',
                'catatan_penanganan' => 'Telah dikoordinasikan dengan petugas PLN Telanaipura untuk perapian dahan besok pagi.',
                'penanggung_jawab_id' => $ketuaRt->id,
            ],
        ];

        foreach ($complaints as $c) {
            Complaint::firstOrCreate(['judul' => $c['judul']], $c);
        }

        // 3. Agenda Kegiatan (5 data)
        $events = [
            [
                'judul' => 'Kerja Bakti & Normalisasi Drainase Lingkungan RT 15',
                'deskripsi' => 'Kegiatan gotong royong membersihkan selokan, merapikan tanaman liar di jalan lingkungan, dan pengecatan pos ronda.',
                'lokasi' => 'Titik Kumpul: Pos Kamling Utama RT 15',
                'tanggal_mulai' => Carbon::now()->addDays(3)->setTime(7, 30),
                'tanggal_selesai' => Carbon::now()->addDays(3)->setTime(11, 00),
                'kategori' => 'kerja_bakti',
                'penyelenggara' => 'Pengurus RT 15',
                'is_recurring' => false,
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Rapat Koordinasi Bulanan Pengurus RT 15',
                'deskripsi' => 'Evaluasi program kerja kebersihan, laporan buku kas bulanan, dan pembahasan perbaikan portal gerbang keamanan.',
                'lokasi' => 'Rumah Ketua RT 15 Pematang Sulur',
                'tanggal_mulai' => Carbon::now()->addDays(7)->setTime(19, 30),
                'tanggal_selesai' => Carbon::now()->addDays(7)->setTime(21, 30),
                'kategori' => 'rapat',
                'penyelenggara' => 'Pengurus RT 15',
                'is_recurring' => false,
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Pengajian Rutin & Santunan Warga RT 15',
                'deskripsi' => 'Pengajian malam Jumat berkah bersama warga dan silaturahmi antar tetangga.',
                'lokasi' => 'Musholla Al-Ikhlas RT 15',
                'tanggal_mulai' => Carbon::now()->addDays(12)->setTime(18, 30),
                'tanggal_selesai' => Carbon::now()->addDays(12)->setTime(20, 30),
                'kategori' => 'pengajian',
                'penyelenggara' => 'Majelis Taklim Musholla Al-Ikhlas',
                'is_recurring' => false,
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Senam Jantung Sehat & Jalan Santai Keluarga RT 15',
                'deskripsi' => 'Senam aerobik dan jalan santai keliling komplek bersama instruktur profesional dilanjutkan sarapan bubur kacang hijau bersama.',
                'lokasi' => 'Lapangan Fasum RT 15',
                'tanggal_mulai' => Carbon::now()->addDays(5)->setTime(6, 30),
                'tanggal_selesai' => Carbon::now()->addDays(5)->setTime(8, 30),
                'kategori' => 'perayaan',
                'penyelenggara' => 'Seksi Olahraga & Pemuda RT 15',
                'is_recurring' => false,
                'dibuat_oleh' => $ketuaRt->id,
            ],
            [
                'judul' => 'Posyandu Balita Bunga Melati & Skrining Lansia',
                'deskripsi' => 'Pelayanan imunisasi balita, penimbangan berat badan, pemberian vitamin A, dan konsultasi kesehatan geriatri lansia.',
                'lokasi' => 'Balai Posyandu RT 15',
                'tanggal_mulai' => Carbon::now()->addDays(10)->setTime(8, 00),
                'tanggal_selesai' => Carbon::now()->addDays(10)->setTime(11, 30),
                'kategori' => 'lainnya',
                'penyelenggara' => 'Kader Posyandu PKK RT 15',
                'is_recurring' => false,
                'dibuat_oleh' => $sekretaris->id,
            ],
        ];

        $createdEvents = [];
        foreach ($events as $ev) {
            $createdEvents[] = Event::firstOrCreate(['judul' => $ev['judul']], $ev);
        }

        // 3b. Konfirmasi Kehadiran Kegiatan (EventAttendance - minimal 5 data)
        if (!empty($createdEvents) && $wargaUsers->isNotEmpty()) {
            $targetEvent = $createdEvents[0];
            $statuses = ['hadir', 'hadir', 'tidak_hadir', 'mungkin', 'hadir', 'hadir'];
            foreach ($wargaUsers as $idx => $usr) {
                EventAttendance::firstOrCreate(
                    [
                        'event_id' => $targetEvent->id,
                        'user_id' => $usr->id,
                    ],
                    [
                        'status' => $statuses[$idx % count($statuses)],
                    ]
                );
            }
        }

        // 4. Lapor Tamu 1x24 Jam (5 data)
        if ($households->isNotEmpty()) {
            $guests = [
                [
                    'household_id' => $households[0]->id,
                    'nama_tamu' => 'Ahmad Fauzi Rahman',
                    'nik_tamu' => '1371011508850001',
                    'asal' => 'Kota Padang, Sumatera Barat',
                    'tujuan' => 'Kunjungan silaturahmi keluarga dan menghadiri hajatan saudara',
                    'tanggal_datang' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'tanggal_pulang' => Carbon::now()->addDays(3)->format('Y-m-d'),
                    'dilaporkan_oleh' => $ketuaRt->id,
                ],
                [
                    'household_id' => $households[0]->id,
                    'nama_tamu' => 'Riko Setiawan',
                    'nik_tamu' => '1671022409920002',
                    'asal' => 'Kota Palembang, Sumatera Selatan',
                    'tujuan' => 'Keperluan urusan pekerjaan proyek instalasi jaringan',
                    'tanggal_datang' => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'tanggal_pulang' => Carbon::now()->addDays(2)->format('Y-m-d'),
                    'dilaporkan_oleh' => $ketuaRt->id,
                ],
                [
                    'household_id' => $households[1]->id ?? $households[0]->id,
                    'nama_tamu' => 'Hendri Pratama',
                    'nik_tamu' => '1375011002900003',
                    'asal' => 'Bukittinggi, Sumatera Barat',
                    'tujuan' => 'Menginap liburan cuti kerja di rumah paman',
                    'tanggal_datang' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'tanggal_pulang' => Carbon::now()->addDays(4)->format('Y-m-d'),
                    'dilaporkan_oleh' => $ketuaRt->id,
                ],
                [
                    'household_id' => $households[2]->id ?? $households[0]->id,
                    'nama_tamu' => 'Budi Santoso',
                    'nik_tamu' => '1505012506880004',
                    'asal' => 'Muaro Jambi, Jambi',
                    'tujuan' => 'Menemani orang tua berobat rujukan ke RSUD Raden Mattaher',
                    'tanggal_datang' => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'tanggal_pulang' => Carbon::now()->addDays(5)->format('Y-m-d'),
                    'dilaporkan_oleh' => $ketuaRt->id,
                ],
                [
                    'household_id' => $households[3]->id ?? $households[0]->id,
                    'nama_tamu' => 'Siti Zulaikha',
                    'nik_tamu' => '1501026011940005',
                    'asal' => 'Sungai Penuh, Kerinci',
                    'tujuan' => 'Keperluan pendaftaran seleksi CPNS di kantor BKD Provinsi Jambi',
                    'tanggal_datang' => Carbon::now()->format('Y-m-d'),
                    'tanggal_pulang' => Carbon::now()->addDays(3)->format('Y-m-d'),
                    'dilaporkan_oleh' => $ketuaRt->id,
                ],
            ];

            foreach ($guests as $g) {
                GuestReport::firstOrCreate(['nik_tamu' => $g['nik_tamu']], $g);
            }
        }

        // 5. Jadwal Ronda Malam (PatrolSchedule & PatrolMember - 6 jadwal)
        $schedules = [
            [
                'tanggal' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                'regu' => 'Regu A - Garuda (Senin Malam)',
                'waktu_mulai' => '22:00',
                'waktu_selesai' => '04:00',
                'catatan' => 'Patroli keliling setiap 1 jam sekali dan pencatatan portal masuk tamu.',
            ],
            [
                'tanggal' => Carbon::now()->startOfWeek()->addDay()->format('Y-m-d'),
                'regu' => 'Regu B - Rajawali (Selasa Malam)',
                'waktu_mulai' => '22:00',
                'waktu_selesai' => '04:00',
                'catatan' => 'Kondisi lingkungan aman kondusif, cuaca cerah.',
            ],
            [
                'tanggal' => Carbon::now()->startOfWeek()->addDays(2)->format('Y-m-d'),
                'regu' => 'Regu C - Elang (Rabu Malam)',
                'waktu_mulai' => '22:00',
                'waktu_selesai' => '04:00',
                'catatan' => 'Kunci portal dipegang ketua regu, senter baterai terisi penuh.',
            ],
            [
                'tanggal' => Carbon::now()->startOfWeek()->addDays(3)->format('Y-m-d'),
                'regu' => 'Regu D - Harimau (Kamis Malam)',
                'waktu_mulai' => '22:00',
                'waktu_selesai' => '04:00',
                'catatan' => 'Pemeriksaan rutin pintu gerbang blok perumahan dan pos ronda.',
            ],
            [
                'tanggal' => Carbon::now()->startOfWeek()->addDays(4)->format('Y-m-d'),
                'regu' => 'Regu E - Banteng (Jumat Malam)',
                'waktu_mulai' => '22:00',
                'waktu_selesai' => '04:00',
                'catatan' => 'Antisipasi peningkatan arus keluar masuk kendaraan tamu akhir pekan.',
            ],
            [
                'tanggal' => Carbon::now()->startOfWeek()->addDays(5)->format('Y-m-d'),
                'regu' => 'Regu F - Singa (Sabtu Malam)',
                'waktu_mulai' => '22:00',
                'waktu_selesai' => '04:00',
                'catatan' => 'Jaga malam akhir pekan dan monitoring berkala posko keamanan.',
            ],
        ];

        foreach ($schedules as $idx => $s) {
            $schedule = PatrolSchedule::firstOrCreate(
                ['tanggal' => $s['tanggal'], 'regu' => $s['regu']],
                $s
            );

            if ($residents->isNotEmpty() && $schedule->members()->count() === 0) {
                // Ambil 3 resident bergantian untuk tiap regu
                $memberResidents = $residents->slice(($idx * 2) % max(1, $residents->count() - 2), 3);
                foreach ($memberResidents as $res) {
                    PatrolMember::create([
                        'patrol_schedule_id' => $schedule->id,
                        'resident_id' => $res->id,
                        'is_pengganti' => false,
                    ]);
                }
            }
        }
    }
}
