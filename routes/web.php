<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/pegawais/template', [PegawaiController::class, 'downloadTemplate'])->name('pegawais.template');
    Route::get('/pegawais/import', [PegawaiController::class, 'import'])->name('pegawais.import');
    Route::post('/pegawais/bulk/preview', [PegawaiController::class, 'previewBulk'])->name('pegawais.bulk.preview');
    Route::post('/pegawais/bulk/confirm', [PegawaiController::class, 'confirmBulk'])->name('pegawais.bulk.confirm');
    Route::resource('pegawais', PegawaiController::class)->except(['show']);

    Route::get('/sertifikats', [SertifikatController::class, 'index'])->name('sertifikats.index');
    Route::post('/sertifikats', [SertifikatController::class, 'store'])->name('sertifikats.store');
    Route::get('/sertifikats/{sertifikat}', [SertifikatController::class, 'show'])->name('sertifikats.show');
    Route::match(['put', 'patch'], '/sertifikats/{sertifikat}', [SertifikatController::class, 'update'])->name('sertifikats.update');
    Route::delete('/sertifikats/{sertifikat}', [SertifikatController::class, 'destroy'])->name('sertifikats.destroy');
});

require __DIR__.'/auth.php';
