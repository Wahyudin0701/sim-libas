<?php

use App\Http\Controllers\AdminNotificationController;
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
Route::get('/struktur-pengurus', [PublicPortalController::class, 'strukturPengurus'])->name('public.struktur-pengurus');
Route::get('/struktur-organisasi', fn () => redirect()->route('public.struktur-pengurus'));
Route::get('/struktur', fn () => redirect()->route('public.struktur-pengurus'));
Route::get('/layanan', [PublicPortalController::class, 'layananIndex'])->name('public.layanan.index');
Route::get('/layanan/{slug}', [PublicPortalController::class, 'layananDetail'])->name('public.layanan.detail');
Route::get('/katalog-umkm', [PublicPortalController::class, 'umkm'])->name('public.umkm');
Route::get('/katalog-umkm/{id}', [PublicPortalController::class, 'umkmDetail'])->name('public.umkm.detail');
Route::get('/pengumuman/{id}', [PublicPortalController::class, 'pengumumanDetail'])->name('public.pengumuman.detail');
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
    Route::get('/login', fn () => redirect()->route('citizen.login'))->name('login');
    Route::post('/masuk', [CitizenAuthController::class, 'login'])->name('citizen.login.post');
});

Route::post('/keluar', [CitizenAuthController::class, 'logout'])->name('citizen.logout')->middleware('auth');


// --- 3. Portal Layanan Mandiri Warga RT 15 (Wajib Login) ---
Route::middleware(['auth'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [CitizenPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/kartu-keluarga', [CitizenPortalController::class, 'kartuKeluarga'])->name('kartu-keluarga');
    Route::get('/profil', [CitizenPortalController::class, 'profile'])->name('profile');
    Route::put('/profil/email', [CitizenPortalController::class, 'updateEmail'])->name('profile.email');
    Route::put('/profil/password', [CitizenPortalController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profil/telepon', [CitizenPortalController::class, 'updatePhone'])->name('profile.phone');
    
    // Surat Pengantar
    Route::get('/surat', [CitizenPortalController::class, 'letters'])->name('surat');
    Route::post('/surat', [CitizenPortalController::class, 'storeLetter'])->name('surat.store');

    // Iuran & Pembayaran
    Route::get('/iuran', [CitizenPortalController::class, 'invoices'])->name('iuran');
    Route::post('/iuran/{invoice}/bayar', [CitizenPortalController::class, 'storePayment'])->name('iuran.pay');

    // Pengaduan Warga
    Route::get('/pengaduan', [CitizenPortalController::class, 'complaints'])->name('pengaduan');
    Route::post('/pengaduan', [CitizenPortalController::class, 'storeComplaint'])->name('pengaduan.store');

    // Notifikasi Sistem Warga
    Route::get('/notifikasi', [CitizenPortalController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifikasi/{notification}/baca', [CitizenPortalController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [CitizenPortalController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
});

// --- 4. Cetak Lembar Resmi Surat Pengantar RT 15 (Admin & Warga) ---
Route::get('/surat/{letter}/cetak', [CitizenPortalController::class, 'printLetter'])
    ->name('letters.print')
    ->middleware('auth');

// --- 5. API Notifikasi Panel Pengurus SIM-LiBas RT 15 (Filament Topbar) ---
Route::middleware(['auth'])->prefix('admin/api')->name('admin.api.')->group(function () {
    Route::get('/notifikasi', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{notification}/baca', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// --- 6. Ekspor Data Panel Admin (Semua Resource) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dedicated household routes
    Route::prefix('households/export')->name('households.export.')->group(function () {
        Route::get('/pdf', [\App\Http\Controllers\HouseholdExportController::class, 'exportPdf'])->name('pdf');
        Route::get('/excel', [\App\Http\Controllers\HouseholdExportController::class, 'exportExcel'])->name('excel');
    });

    // Unified export routes for all 16 resources
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/{resource}/pdf', [\App\Http\Controllers\AdminExportController::class, 'exportPdf'])->name('pdf');
        Route::get('/{resource}/excel', [\App\Http\Controllers\AdminExportController::class, 'exportExcel'])->name('excel');
    });
});

