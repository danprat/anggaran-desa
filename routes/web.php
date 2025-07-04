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
});

require __DIR__.'/auth.php';
