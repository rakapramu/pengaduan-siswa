<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\GuruController;
use App\Http\Controllers\Dashboard\KonselingController;
use App\Http\Controllers\Dashboard\PengaduanController;
use App\Http\Controllers\Dashboard\SiswaController;
use App\Models\Guru;
use App\Models\Pengaduan;
use App\Models\SesiKonseling;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('loginAction', [AuthController::class, 'loginAction'])->name('loginAction');
});

Route::middleware('auth')->prefix('admin-panel')->group(function () {
    Route::get('/', function () {
        $title = '';
        $siswaCount = Siswa::count();
        $guruCount = Guru::count();
        $aduanHariIni = Pengaduan::whereDate('created_at', Carbon::today())->count();
        $konselingMasuk = SesiKonseling::whereDate('created_at', Carbon::today())->count();
        $konselingPerBulan = SesiKonseling::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Pastikan array berisi 12 bulan
        $dataKonseling = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataKonseling[] = $konselingPerBulan[$i] ?? 0;
        }

        // Donut chart siswa
        $laki = Siswa::where('jenis_kelamin', 'L')->count();
        $perempuan = Siswa::where('jenis_kelamin', 'P')->count();
        return view('dashboard.index', compact('siswaCount', 'guruCount', 'aduanHariIni', 'konselingMasuk', 'dataKonseling', 'laki', 'perempuan', 'title'));
    })->name('dashboard');

    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::get('siswa-import', function () {
        $title = 'siswa';
        return view('dashboard.siswa.import', compact('title'));
    })->name('siswa.import');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa-import.post');

    Route::resource('konseling', KonselingController::class);
    Route::get('konseling-export', [KonselingController::class, 'export'])->name('konseling.export');
    Route::get('konseling-notif-read/{konseling}', [KonselingController::class, 'readNotification'])->name('konseling.notif.read');
    Route::post('logout', [AuthController::class, 'logoutAction'])->name('logout');
    Route::resource('pengaduan', PengaduanController::class);
    Route::get('pengaduan-export', [PengaduanController::class, 'export'])->name('pengaduan.export');
    Route::put('pengaduan/status/{pengaduan}', [PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
});
