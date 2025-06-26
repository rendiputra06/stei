<?php

use App\Livewire\StatusMahasiswaPage;
use App\Http\Controllers\CustomFilamentLoginController;
use App\Http\Controllers\LoginAsController;
use App\Http\Controllers\KHSCetakController;
use App\Http\Controllers\TranskripCetakController;
use App\Http\Controllers\ProgramStudiController;
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
    return view('welcome4');
});

// Route untuk home yang akan redirect ke panel tertentu
Route::get('/home', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('super_admin')) {
        return redirect()->route('filament.admin.pages.dashboard');
    } elseif ($user->hasRole('dosen')) {
        return redirect()->route('filament.dosen.pages.dashboard');
    } elseif ($user->hasRole('mahasiswa')) {
        return redirect()->route('filament.mahasiswa.pages.dashboard');
    }

    // Default redirect jika role tidak dikenali
    return redirect()->route('login');
})->middleware(['auth'])->name('home');

// Route untuk preview landing page variations
Route::prefix('preview')->group(function () {
    Route::get('/landing-1', function () {
        return view('welcome1');
    })->name('landing.preview1');

    Route::get('/landing-2', function () {
        return view('welcome2');
    })->name('landing.preview2');

    Route::get('/landing-3', function () {
        return view('welcome3');
    })->name('landing.preview3');

    Route::get('/landing-4', function () {
        return view('welcome4');
    })->name('landing.preview4');

    Route::get('/landing-5', function () {
        return view('welcome5');
    })->name('landing.preview5');
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
    if (auth()->user()->id !== $krs->mahasiswa->user_id) {
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

// Route untuk cetak Transkrip Nilai
Route::get('/transkrip/cetak', [TranskripCetakController::class, 'cetakTranskrip'])
    ->name('transkrip.cetak')
    ->middleware(['auth']);

Route::prefix('program-studi')->group(function () {
    Route::get('/', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('/visi-misi', [ProgramStudiController::class, 'visiMisi'])->name('program-studi.visi-misi');
    Route::get('/kurikulum', [ProgramStudiController::class, 'kurikulum'])->name('program-studi.kurikulum');
    Route::get('/profil-lulusan', [ProgramStudiController::class, 'profilLulusan'])->name('program-studi.profil-lulusan');
    Route::get('/prospek-karir', [ProgramStudiController::class, 'prospekKarir'])->name('program-studi.prospek-karir');
    Route::get('/dosen', [ProgramStudiController::class, 'dosen'])->name('program-studi.dosen');
    Route::get('/fasilitas', [ProgramStudiController::class, 'fasilitas'])->name('program-studi.fasilitas');
});
