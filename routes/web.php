<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    HalamanController,
    RekomendasiController,
    RiwayatController,
    ProfileController
};

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Akses Bebas)
|--------------------------------------------------------------------------
*/
Route::get('/', [HalamanController::class, 'index'])->name('home');

// Rekomendasi (Hybrid: Bisa diakses Guest tapi Simpan harus Auth)
Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
Route::post('/rekomendasi/proses', [RekomendasiController::class, 'proses'])->name('rekomendasi.proses');
Route::get('/rekomendasi/proses', fn() => redirect()->route('rekomendasi.index'));

// Riwayat Index (DIPINDAH KE SINI agar Guest bisa lihat Popup SweetAlert)
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');


/*
|--------------------------------------------------------------------------
| GUEST ONLY (Hanya sebelum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'halamanLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'prosesLogin']);
    Route::get('/register', [AuthController::class, 'halamanRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'prosesRegister']);
    
    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'googleRedirect'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

    // Lupa & Reset Password
    Route::get('/forgot-password', [AuthController::class, 'halamanLupaPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'kirimLinkReset'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'halamanResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'prosesResetPassword'])->name('password.update');
});


/*
|--------------------------------------------------------------------------
| AUTH ONLY (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::post('/profile/edit', 'update')->name('profile.update');
    });

    // Riwayat Detail & Aksi (Tetap diproteksi agar data aman)
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    // Simpan Rekomendasi
    Route::post('/rekomendasi/simpan', [RekomendasiController::class, 'simpan'])->name('rekomendasi.simpan');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});