<?php

use App\Livewire\StatusMahasiswaPage;
use App\Http\Controllers\CustomFilamentLoginController;
use App\Http\Controllers\LoginAsController;
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
