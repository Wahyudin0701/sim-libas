<?php

namespace Database\Seeders;

use App\Models\PatrolSchedule;
use Illuminate\Database\Seeder;

class PatrolScheduleMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi Master Jadwal Ronda Mingguan 7 Hari (Senin s/d Minggu)
     * yang menjadi referensi utama pelaksanaan ronda malam lingkungan RT 15.
     */
    public function run(): void
    {
        $masterSchedules = [
            [
                'hari_ke' => 1,
                'regu' => 'Regu A - Garuda (Senin Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Fokus patroli gerbang barat dan area pos ronda utama.',
            ],
            [
                'hari_ke' => 2,
                'regu' => 'Regu B - Rajawali (Selasa Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Kondisi lingkungan aman kondusif, cuaca cerah.',
            ],
            [
                'hari_ke' => 3,
                'regu' => 'Regu C - Elang (Rabu Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Kunci portal dipegang ketua regu, senter baterai terisi penuh.',
            ],
            [
                'hari_ke' => 4,
                'regu' => 'Regu D - Harimau (Kamis Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Pemeriksaan rutin pintu gerbang blok perumahan dan pos ronda.',
            ],
            [
                'hari_ke' => 5,
                'regu' => 'Regu E - Banteng (Jumat Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Antisipasi peningkatan arus keluar masuk kendaraan tamu akhir pekan.',
            ],
            [
                'hari_ke' => 6,
                'regu' => 'Regu F - Singa (Sabtu Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Jaga malam akhir pekan dan monitoring berkala posko keamanan.',
            ],
            [
                'hari_ke' => 7,
                'regu' => 'Regu G - Merpati (Minggu Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'is_aktif' => true,
                'catatan' => 'Ronda awal pekan dan pemantauan lampu penerangan jalan.',
            ],
        ];

        foreach ($masterSchedules as $scheduleData) {
            PatrolSchedule::firstOrCreate(
                ['hari_ke' => $scheduleData['hari_ke']],
                $scheduleData
            );
        }
    }
}
