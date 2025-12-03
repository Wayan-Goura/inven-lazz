<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('dashboard'))->name('dashboard');
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', fn() => view('pages.barang.index'))->name('index');       // index.blade.php
    Route::get('/create', fn() => view('pages.barang.create'))->name('create'); // create.blade.php
    Route::get('/edit', fn() => view('pages.barang.edit'))->name('edit');       // edit.blade.php
});
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/masuk', fn() => view('pages.kel_barang.b_masuk'))->name('masuk');
    Route::get('/keluar', fn() => view('pages.kel_barang.b_keluar'))->name('keluar');
    Route::get('/return', fn() => view('pages.kel_barang.b_return'))->name('return');
});
Route::get('/toko', fn() => view('pages.toko'))->name('toko');
Route::get('/profile', fn() => view('pages.profile'))->name('profile');
Route::get('/logout', fn() => "Logout berhasil (dummy).")->name('logout');
