<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kegiatan routes
    Route::resource('kegiatan', App\Http\Controllers\KegiatanController::class);
    Route::post('kegiatan/{kegiatan}/verify', [App\Http\Controllers\KegiatanController::class, 'verify'])->name('kegiatan.verify');
    Route::post('kegiatan/{kegiatan}/approve', [App\Http\Controllers\KegiatanController::class, 'approve'])->name('kegiatan.approve');
    Route::post('kegiatan/{kegiatan}/reject', [App\Http\Controllers\KegiatanController::class, 'reject'])->name('kegiatan.reject');
    Route::get('kegiatan/{kegiatan}/export-pdf', [App\Http\Controllers\KegiatanController::class, 'exportPdf'])->name('kegiatan.export-pdf');
    Route::get('kegiatan/{kegiatan}/print', [App\Http\Controllers\KegiatanController::class, 'print'])->name('kegiatan.print');

    // Realisasi routes
    Route::resource('realisasi', App\Http\Controllers\RealisasiController::class);
    Route::delete('bukti-file/{buktiFile}', [App\Http\Controllers\RealisasiController::class, 'deleteBukti'])->name('bukti-file.delete');

    // Laporan routes
    Route::get('laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/{type}', [App\Http\Controllers\LaporanController::class, 'show'])->name('laporan.show');
    Route::post('laporan/{type}/pdf', [App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::post('laporan/{type}/excel', [App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.excel');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::resource('tahun-anggaran', App\Http\Controllers\Admin\TahunAnggaranController::class);
        Route::post('tahun-anggaran/{tahunAnggaran}/set-aktif', [App\Http\Controllers\Admin\TahunAnggaranController::class, 'setAktif'])->name('tahun-anggaran.set-aktif');

        // Desa Profile routes
        Route::resource('desa-profile', App\Http\Controllers\Admin\DesaProfileController::class);
        Route::post('desa-profile/{desa_profile}/set-active', [App\Http\Controllers\Admin\DesaProfileController::class, 'setActive'])->name('desa-profile.set-active');
        Route::get('desa-profile/{desa_profile}/export-pdf', [App\Http\Controllers\Admin\DesaProfileController::class, 'exportPdf'])->name('desa-profile.export-pdf');
    });

    // Log Aktivitas routes
    Route::get('log', [App\Http\Controllers\LogAktivitasController::class, 'index'])->name('log.index');
    Route::get('log/{log}', [App\Http\Controllers\LogAktivitasController::class, 'show'])->name('log.show');
    Route::get('log-export', [App\Http\Controllers\LogAktivitasController::class, 'export'])->name('log.export');
});

require __DIR__.'/auth.php';
