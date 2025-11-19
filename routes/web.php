<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\GuruController;
use App\Http\Controllers\Dashboard\KonselingController;
use App\Http\Controllers\Dashboard\SiswaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('loginAction', [AuthController::class, 'loginAction'])->name('loginAction');
});

Route::middleware('auth')->prefix('admin-panel')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);

    Route::resource('konseling', KonselingController::class);
    Route::post('logout', [AuthController::class, 'logoutAction'])->name('logout');
});
