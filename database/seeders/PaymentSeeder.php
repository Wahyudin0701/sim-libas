<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi data pembayaran dan validasi iuran warga
     */
    public function run(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.com')->first()
            ?? User::where('email', 'rt@simlibas.com')->first()
            ?? User::first();

        $invoices = Invoice::all();
        if ($invoices->isEmpty()) {
            return;
        }

        // 1. Pembayaran Valid (Invoice 1 - Lunas)
        if ($inv1 = $invoices->find(1)) {
            Payment::firstOrCreate(
                ['invoice_id' => $inv1->id],
                [
                    'jumlah' => $inv1->jumlah,
                    'metode' => 'transfer',
                    'catatan' => 'Transfer via BCA Mobile a.n Dedi Kurniawan',
                    'tanggal_bayar' => Carbon::now()->subDays(5)->format('Y-m-d'),
                    'divalidasi_oleh' => $bendahara?->id,
                    'tanggal_validasi' => Carbon::now()->subDays(4),
                    'status' => 'valid',
                ]
            );
            $inv1->update(['status' => 'lunas']);
        }

        // 2. Pembayaran Pending / Menunggu Verifikasi (Invoice 2)
        if ($inv2 = $invoices->find(2)) {
            Payment::firstOrCreate(
                ['invoice_id' => $inv2->id],
                [
                    'jumlah' => $inv2->jumlah,
                    'metode' => 'transfer',
                    'catatan' => 'Konfirmasi pembayaran iuran keamanan via upload bukti transfer',
                    'tanggal_bayar' => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'status' => 'pending',
                ]
            );
        }

        // 3. Pembayaran Tunai Valid (Invoice 4 - Lunas)
        if ($inv4 = $invoices->find(4)) {
            Payment::firstOrCreate(
                ['invoice_id' => $inv4->id],
                [
                    'jumlah' => $inv4->jumlah,
                    'metode' => 'tunai',
                    'catatan' => 'Bayar tunai ke bendahara saat ronda malam',
                    'tanggal_bayar' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'divalidasi_oleh' => $bendahara?->id,
                    'tanggal_validasi' => Carbon::now()->subDays(3),
                    'status' => 'valid',
                ]
            );
            $inv4->update(['status' => 'lunas']);
        }

        // 4. Pembayaran Ditolak (Invoice 5)
        if ($inv5 = $invoices->find(5)) {
            Payment::firstOrCreate(
                ['invoice_id' => $inv5->id],
                [
                    'jumlah' => $inv5->jumlah,
                    'metode' => 'transfer',
                    'catatan' => 'Foto bukti transfer buram dan tidak terbaca jelas. Mohon unggah ulang.',
                    'tanggal_bayar' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'divalidasi_oleh' => $bendahara?->id,
                    'tanggal_validasi' => Carbon::now()->subDays(1),
                    'status' => 'ditolak',
                ]
            );
        }

        // 5. Pembayaran Transfer Valid (Invoice 6 - Lunas)
        if ($inv6 = $invoices->find(6)) {
            Payment::firstOrCreate(
                ['invoice_id' => $inv6->id],
                [
                    'jumlah' => $inv6->jumlah,
                    'metode' => 'transfer',
                    'catatan' => 'Pembayaran iuran transfer QRIS Bank 9 Jambi RT 15',
                    'tanggal_bayar' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'divalidasi_oleh' => $bendahara?->id,
                    'tanggal_validasi' => Carbon::now()->subDays(2),
                    'status' => 'valid',
                ]
            );
            $inv6->update(['status' => 'lunas']);
        }

        // 6. Pembayaran Tunai Menunggu Validasi (Invoice 7)
        if ($inv7 = $invoices->find(7)) {
            Payment::firstOrCreate(
                ['invoice_id' => $inv7->id],
                [
                    'jumlah' => $inv7->jumlah,
                    'metode' => 'tunai',
                    'catatan' => 'Titip tunai ke koordinator lingkungan Blok B',
                    'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
                    'status' => 'pending',
                ]
            );
        }
    }
}
