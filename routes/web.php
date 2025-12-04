<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('dashboard'))->name('dashboard');
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', fn() => view('pages.barang.index'))->name('index');       // index.blade.php
    Route::get('/create', fn() => view('pages.barang.create'))->name('create'); // create.blade.php
    Route::get('/edit', fn() => view('pages.barang.edit'))->name('edit');       // edit.blade.php
});
Route::prefix('barang')->name('barang.')->group(function () {

    // Barang Masuk
    Route::prefix('masuk')->name('masuk.')->group(function () {
        Route::get('/', fn() => view('pages.kel_barang.b_masuk.index'))->name('index');
        Route::get('/create', fn() => view('pages.kel_barang.b_masuk.create'))->name('create');
        Route::get('/edit/{id}', fn($id) => view('pages.kel_barang.b_masuk.edit', compact('id')))->name('edit');
    });

    // Barang Keluar
    Route::prefix('keluar')->name('keluar.')->group(function () {
        Route::get('/', fn() => view('pages.kel_barang.b_keluar.index'))->name('index');
        Route::get('/create', fn() => view('pages.kel_barang.b_keluar.create'))->name('create');
        Route::get('/edit/{id}', fn($id) => view('pages.kel_barang.b_keluar.edit', compact('id')))->name('edit');
    });

    // Barang Return
    Route::prefix('return')->name('return.')->group(function () {
        Route::get('/', fn() => view('pages.kel_barang.b_return.index'))->name('index');
        Route::get('/create', fn() => view('pages.kel_barang.b_return.create'))->name('create');
        Route::get('/edit/{id}', fn($id) => view('pages.kel_barang.b_return.edit', compact('id')))->name('edit');
    });

});

Route::get('/toko', fn() => view('pages.toko'))->name('toko');
Route::get('/profile', fn() => view('pages.profile'))->name('profile');
Route::get('/logout', fn() => "Logout berhasil (dummy).")->name('logout');
