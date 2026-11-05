<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('lapor.form');
})->name('home');

Route::get('/lapor', [LaporanController::class, 'publicForm'])->name('lapor.form');
Route::post('/lapor', [LaporanController::class, 'submitByWarga'])->name('lapor.submit');
Route::get('/riwayat-laporan', [LaporanController::class, 'history'])
    ->middleware('auth')
    ->name('riwayat.index');
Route::get('/lacak-laporan/{trackingToken}', [LaporanController::class, 'track'])
    ->middleware('auth')
    ->name('laporan.track');
Route::get('/laporan/{laporan}/foto', [LaporanController::class, 'foto'])->name('laporan.foto');

Route::middleware(['auth', 'petugas'])->group(function () {
    Route::patch('laporan/{laporan}/status', [LaporanController::class, 'updateStatus'])
        ->name('laporan.status.update');
    Route::resource('laporan', LaporanController::class);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'petugas'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
