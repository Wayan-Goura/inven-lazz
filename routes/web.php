<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
| Akses pertama → halaman login
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:super_admin')->group(function () {

        Route::get('/dashboard', fn () => view('dashboard'))
            ->name('dashboard');

        // Kelola Admin
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN & SUPER ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,super_admin')->group(function () {

        /*
        |-------------------------------
        | TRANSAKSI BARANG
        |-------------------------------
        */
        Route::get('/barang-masuk', [TransaksiController::class, 'b_masuk'])
            ->name('kel_barang.b_masuk.index');

        Route::get('/barang-keluar', [TransaksiController::class, 'b_keluar'])
            ->name('kel_barang.b_keluar.index');

        /*
        |-------------------------------
        | DATA BARANG (CRUD)
        |-------------------------------
        */
        Route::resource('barang', DataBarangController::class)->except(['show']);

        Route::get('barang/pdf-mpdf', [DataBarangController::class, 'cetak_pdf'])
            ->name('barang.cetak_pdf');

        /*
        |-------------------------------
        | TRANSAKSI DETAIL
        |-------------------------------
        */
        Route::prefix('transaksi')->name('transaksi.')->group(function () {

            Route::get('/create', fn () => view('pages.transaksi.create'))->name('create');
            Route::post('/', [TransaksiController::class, 'store'])->name('store');

            Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [TransaksiController::class, 'update'])->name('update');

            Route::get('/keluar', [TransaksiController::class, 'b_keluar'])->name('keluar.index');

            Route::get('/masuk', fn () => view('pages.kel_barang.b_masuk.index'))->name('masuk.index');
            // transaction views (like Masuk, Return)
            Route::get('/masuk', fn() => view('pages.kel_barang.b_masuk.index'))->name('masuk.index');
            Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('destroy');

            Route::prefix('return')->name('return.')->group(function () {
                Route::get('/', fn () => view('pages.kel_barang.b_return.index'))->name('index');
                Route::get('/create', fn () => view('pages.kel_barang.b_return.create'))->name('create');
                Route::get('/edit/{id}', fn ($id) =>
                    view('pages.kel_barang.b_return.edit', compact('id'))
                )->name('edit');
            });
        });

        /*
        |-------------------------------
        | KATEGORI BARANG
        |-------------------------------
        */
        Route::prefix('kel_barang/catagory')->name('kel_barang.catagory.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        /*
        |-------------------------------
        | PAGE UMUM
        |-------------------------------
        */
/*
|-------------------------------
| KELOLA ROLE / ADMIN
|-------------------------------
*/
Route::prefix('kel-role')
    ->name('kel_role.')
    ->middleware('role:super_admin')
    ->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
        Route::get('/profile', fn () => view('pages.profile'))->name('profile');
    });
});

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => abort(404));
