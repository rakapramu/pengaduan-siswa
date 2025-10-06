<?php

use App\Http\Controllers\Dashboard\GuruController;
use App\Http\Controllers\Dashboard\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index');
});

Route::resource('guru', GuruController::class);
Route::resource('siswa', SiswaController::class);
