<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('dashboard'))->name('dashboard');
Route::get('/barang', fn() => view('pages.barang'))->name('barang');
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/masuk', fn() => view('pages.kel_barang.b_masuk'))->name('masuk');
    Route::get('/keluar', fn() => view('pages.kel_barang.b_keluar'))->name('keluar');
    Route::get('/return', fn() => view('pages.kel_barang.b_return'))->name('return');
});
Route::get('/toko', fn() => view('pages.toko'))->name('toko');
Route::get('/profile', fn() => view('pages.profile'))->name('profile');
Route::get('/logout', fn() => "Logout berhasil (dummy).")->name('logout');
