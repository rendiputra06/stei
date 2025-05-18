<?php

use App\Livewire\StatusMahasiswaPage;
use App\Http\Controllers\CustomFilamentLoginController;
use App\Http\Controllers\LoginAsController;
use App\Http\Controllers\KHSCetakController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/status-mahasiswa', StatusMahasiswaPage::class)->name('status-mahasiswa');

// Rute autentikasi dasar
Route::get('/login', [CustomFilamentLoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [CustomFilamentLoginController::class, 'login'])
    ->middleware('guest');

Route::post('/logout', [CustomFilamentLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Quick login (only available in local environment)
Route::get('/quick-login/{userId}', [CustomFilamentLoginController::class, 'quickLogin'])
    ->name('filament.custom.quick-login')
    ->middleware('guest');

// Login As and Return Routes
Route::get('/login-as/{user}', [LoginAsController::class, 'loginAs'])
    ->middleware('auth')
    ->name('login-as');

Route::get('/return-to-admin', [LoginAsController::class, 'returnToAdmin'])
    ->middleware('auth')
    ->name('return-to-admin');

// Buat alias rute
Route::get('/filament-login', function () {
    return redirect()->route('login');
})->name('filament.custom.login');

Route::post('/filament-logout', function () {
    return redirect()->route('logout');
})->name('filament.custom.logout');

// Route untuk cetak KRS
Route::get('/krs/{krs}/print', function (App\Models\KRS $krs) {
    // Cek otorisasi
    if (auth()->user()->cannot('view', $krs)) {
        abort(403);
    }

    // Ambil data untuk cetak KRS
    $mahasiswa = $krs->mahasiswa;
    $tahunAkademik = $krs->tahunAkademik;
    $krsDetails = $krs->krsDetail()->with(['mataKuliah', 'jadwal', 'jadwal.dosen', 'jadwal.ruangan'])->get();

    // Return view cetak KRS
    return view('mahasiswa.krs.print', compact('krs', 'mahasiswa', 'tahunAkademik', 'krsDetails'));
})->name('krs.print')->middleware(['auth']);

// Route untuk cetak KHS
Route::get('/khs/cetak/{semester}/{tahunAkademikId}', [KHSCetakController::class, 'cetakKHS'])
    ->name('khs.cetak')
    ->middleware(['auth']);
