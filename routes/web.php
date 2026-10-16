<?php

use App\Http\Controllers\CitizenAuthController;
use App\Http\Controllers\CitizenPortalController;
use App\Http\Controllers\PublicPortalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIM-LiBas RT 15 Pematang Sulur
|--------------------------------------------------------------------------
*/

// --- 1. Rute Publik (Terbuka untuk Umum / Warga Tanpa Login) ---
Route::get('/', [PublicPortalController::class, 'index'])->name('public.home');
Route::get('/layanan', [PublicPortalController::class, 'layananIndex'])->name('public.layanan.index');
Route::get('/layanan/{slug}', [PublicPortalController::class, 'layananDetail'])->name('public.layanan.detail');
Route::get('/katalog-umkm', [PublicPortalController::class, 'umkm'])->name('public.umkm');
Route::get('/bank-sampah', [PublicPortalController::class, 'bankSampah'])->name('public.bank-sampah');
Route::get('/kontak', [PublicPortalController::class, 'contact'])->name('public.contact');

// Verifikasi Surat Pengantar via QR Code (FR-PUB-03)
Route::get('/verifikasi-surat/{token?}', function (Request $request, ?string $token = null) {
    $searchToken = $token ?? $request->query('token', '');
    return app(PublicPortalController::class)->verifyLetter($searchToken);
})->name('public.verify-letter');


// --- 2. Autentikasi Warga ---
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [CitizenAuthController::class, 'showLogin'])->name('citizen.login');
    Route::post('/masuk', [CitizenAuthController::class, 'login'])->name('citizen.login.post');
});

Route::post('/keluar', [CitizenAuthController::class, 'logout'])->name('citizen.logout')->middleware('auth');


// --- 3. Portal Layanan Mandiri Warga RT 15 (Wajib Login) ---
Route::middleware(['auth'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [CitizenPortalController::class, 'dashboard'])->name('dashboard');
    
    // Surat Pengantar
    Route::get('/surat', [CitizenPortalController::class, 'letters'])->name('surat');
    Route::post('/surat', [CitizenPortalController::class, 'storeLetter'])->name('surat.store');

    // Iuran & Pembayaran
    Route::get('/iuran', [CitizenPortalController::class, 'invoices'])->name('iuran');
    Route::post('/iuran/{invoice}/bayar', [CitizenPortalController::class, 'storePayment'])->name('iuran.pay');

    // Pengaduan Warga
    Route::get('/pengaduan', [CitizenPortalController::class, 'complaints'])->name('pengaduan');
    Route::post('/pengaduan', [CitizenPortalController::class, 'storeComplaint'])->name('pengaduan.store');
});
