<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SertifikatPegawaiController;
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

    // Mengelompokkan semua rute untuk PegawaiController
    Route::controller(PegawaiController::class)->prefix('pegawais')->name('pegawais.')->group(function () {
        Route::get('/template', 'downloadTemplate')->name('template');
        Route::get('/import', 'import')->name('import');
        Route::post('/bulk/preview', 'previewBulk')->name('bulk.preview');
        Route::post('/bulk/confirm', 'confirmBulk')->name('bulk.confirm');
    });
    Route::resource('pegawais', PegawaiController::class)->except(['show']);

    // Rute untuk Sertifikat
    Route::resource('sertifikats', SertifikatController::class)->except(['show']);

    // Mengelompokkan semua rute untuk SertifikatPegawaiController
    // Disarankan menggunakan underscore (_) untuk URI multi-kata: sertifikat_pegawai
    Route::controller(SertifikatPegawaiController::class)->prefix('sertifikat-pegawai')->name('sertifikat-pegawai.')->group(function () {
        Route::get('/template', 'downloadTemplate')->name('template');
        Route::get('/import', 'import')->name('import');
        Route::post('/bulk/preview', 'previewBulk')->name('bulk.preview');
        Route::post('/bulk/confirm', 'confirmBulk')->name('bulk.confirm');
    });
    Route::resource('sertifikat-pegawai', SertifikatPegawaiController::class)->except(['show']);
});
require __DIR__.'/auth.php';
