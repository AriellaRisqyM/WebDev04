<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// =====================
// ADMIN CONTROLLERS
// =====================
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\DokterController;

// =====================
// DOKTER CONTROLLERS
// =====================
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;

// =====================
// PASIEN CONTROLLERS
// =====================
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('showlogin');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROLE: ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
});

/*
|--------------------------------------------------------------------------
| ROLE: DOKTER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    // Modul Jadwal Periksa
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    // Modul Periksa Pasien (Penyelesaian Error)
    // 1. Definisikan route 'create' secara manual agar bisa menerima parameter ID pendaftaran
    Route::get('periksa-pasien/create/{id}', [PeriksaPasienController::class, 'create'])
        ->name('periksa.pasien.create');

    // 2. Batasi resource hanya untuk index dan store agar tidak memanggil method 'show' yang tidak ada
    Route::resource('periksa-pasien', PeriksaPasienController::class)
        ->only(['index', 'store'])
        ->names('periksa.pasien');

    // Modul Riwayat Pasien
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])
        ->name('riwayat.pasien.index');

    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])
        ->name('riwayat.pasien.show');
});

/*
|--------------------------------------------------------------------------
| ROLE: PASIEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    // Fitur Pendaftaran Poli Pasien
    Route::get('/daftar', [PasienPoliController::class, 'get'])
        ->name('pasien.daftar');

    Route::post('/daftar', [PasienPoliController::class, 'submit'])
        ->name('pasien.daftar.submit');
    // Riwayat Periksa (Baru)
    Route::get('/riwayat', [PasienPoliController::class, 'riwayat'])->name('pasien.riwayat');
});