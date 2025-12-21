<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// // 3. User Routes
// Route::get('/users', [UserController::class, 'index'])->name('user.index');
// Route::post('/users', [UserController::class, 'store'])->name('user.store');
// routes/web.php

Route::middleware(['auth'])->group(function () {

    // --- KHUSUS SUPERADMIN ---
    Route::middleware(['role:superadmin'])->group(function () {
        // Hanya superadmin yang bisa lihat dashboard utama
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // Hanya superadmin yang bisa kelola user
        Route::get('/users', [UserController::class, 'index'])->name('user.index');


    });

    // --- KHUSUS ADMIN (Atau Keduanya) ---
    Route::middleware(['role:admin,superadmin'])->group(function () {
        // Admin biasanya langsung diarahkan ke fitur transaksi
        Route::get('/barang-masuk', [TransaksiController::class, 'b_masuk'])->name('kel_barang.b_masuk.index');
        Route::get('/barang-keluar', [TransaksiController::class, 'b_keluar'])->name('kel_barang.b_keluar.index');

        /* keleloa data barang  */
        Route::prefix('barang')->name('barang.')->group(function () {
            Route::get('/', [DataBarangController::class, 'index'])->name('index');
            Route::get('/', [DataBarangController::class, 'index'])->name('index');
            Route::get('/create', [DataBarangController::class, 'create'])->name('create');
            Route::post('/', [DataBarangController::class, 'store'])->name('store');
            Route::get('/barang/{barang}/edit', [DataBarangController::class, 'edit'])->name('barang.edit');
            Route::put('/barang/{barang}', [DataBarangController::class, 'update'])
                ->name('barang.update');
            Route::delete('/{id}', [DataBarangController::class, 'destroy'])->name('destroy');
            route::get('data-barang/pdf-mpdf', [DataBarangController::class, 'cetak_pdf'])->name('cetak_pdf');
        });
        Route::resource('barang', DataBarangController::class)->except(['show']);


        /* route kelola barang masuk, keluar, dan return */
        Route::prefix('kel_barang')
            ->name('kel_barang.')
            ->group(function () {

                // Route Barang Masuk: kel_barang.b_masuk.index
                Route::get('/b_masuk', [TransaksiController::class, 'b_masuk'])->name('b_masuk.index');

                // Route Barang Keluar: kel_barang.b_keluar.index
                Route::get('/b_keluar', [TransaksiController::class, 'b_keluar'])->name('b_keluar.index');
            });

            /* route kelola transaksi */
        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            // New Transaction (was /barang/create)
            Route::get('/create', fn() => view('pages.transaksi.create'))->name('create');
            Route::post('/', [TransaksiController::class, 'store'])->name('store');

            // Existing/Specific Transaction Routes
            Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [TransaksiController::class, 'update'])->name('update');

            // b_keluar (List)
            Route::get('/keluar', [TransaksiController::class, 'b_keluar'])->name('keluar.index');

            // transaction views (like Masuk, Return)
            Route::get('/masuk', fn() => view('pages.kel_barang.b_masuk.index'))->name('masuk.index');
            Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('destroy');

            Route::prefix('return')->name('return.')->group(function () {
                Route::get('/', fn() => view('pages.kel_barang.b_return.index'))->name('index');
                Route::get('/create', fn() => view('pages.kel_barang.b_return.create'))->name('create');
                Route::get('/edit/{id}', fn($id) => view('pages.kel_barang.b_return.edit', compact('id')))->name('edit');
            });
        });

        /* route kelola kategori */
        Route::prefix('kel_barang')->name('kel_barang.')->group(function () {
            Route::prefix('catagory')->name('catagory.')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('index');
                Route::get('/create', [CategoryController::class, 'create'])->name('create');
                Route::post('/', [CategoryController::class, 'store'])->name('store');
                Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
                Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
                Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
                Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            });
        });
    });
});

// 1. Core Routes
Route::get('/', fn() => view('dashboard'))->name('dashboard');
Route::get('/toko', fn() => view('pages.toko'))->name('toko');
Route::get('/profile', fn() => view('pages.profile'))->name('profile');
Route::get('/logout', fn() => "Logout berhasil (dummy).")->name('logout');

// 2. DataBarang (Resource) Routes - hendel /barang, /barang/{id}/edit, etc.
// nama prefix untuk barang: 'barang.'
// Route::prefix('barang')->name('barang.')->group(function () {
//     Route::get('/', [DataBarangController::class, 'index'])->name('index');
//     Route::get('/', [DataBarangController::class, 'index'])->name('index');
//     Route::get('/create', [DataBarangController::class, 'create'])->name('create');
//     Route::post('/', [DataBarangController::class, 'store'])->name('store');
//     Route::get('/barang/{barang}/edit', [DataBarangController::class, 'edit'])->name('barang.edit');
//     Route::put('/barang/{barang}', [DataBarangController::class, 'update'])
//         ->name('barang.update');
//     Route::delete('/{id}', [DataBarangController::class, 'destroy'])->name('destroy');
//     route::get('data-barang/pdf-mpdf', [DataBarangController::class, 'cetak_pdf'])->name('cetak_pdf');
// });
// Route::resource('barang', DataBarangController::class)->except(['show']);

// Route::prefix('kel_barang')
//     ->name('kel_barang.')
//     ->group(function () {

//         // Route Barang Masuk: kel_barang.b_masuk.index
//         Route::get('/b_masuk', [TransaksiController::class, 'b_masuk'])->name('b_masuk.index');

//         // Route Barang Keluar: kel_barang.b_keluar.index
//         Route::get('/b_keluar', [TransaksiController::class, 'b_keluar'])->name('b_keluar.index');
//     });

// 3. TRANSACTION Routes
// Route::prefix('transaksi')->name('transaksi.')->group(function () {
//     // New Transaction (was /barang/create)
//     Route::get('/create', fn() => view('pages.transaksi.create'))->name('create');
//     Route::post('/', [TransaksiController::class, 'store'])->name('store');

//     // Existing/Specific Transaction Routes
//     Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('edit');
//     Route::put('/update/{id}', [TransaksiController::class, 'update'])->name('update');

//     // b_keluar (List)
//     Route::get('/keluar', [TransaksiController::class, 'b_keluar'])->name('keluar.index');

//     // transaction views (like Masuk, Return)
//     Route::get('/masuk', fn() => view('pages.kel_barang.b_masuk.index'))->name('masuk.index');

//     Route::prefix('return')->name('return.')->group(function () {
//         Route::get('/', fn() => view('pages.kel_barang.b_return.index'))->name('index');
//         Route::get('/create', fn() => view('pages.kel_barang.b_return.create'))->name('create');
//         Route::get('/edit/{id}', fn($id) => view('pages.kel_barang.b_return.edit', compact('id')))->name('edit');
//     });
// });

// 4. Category Routes

// Route::prefix('kel_barang')->name('kel_barang.')->group(function () {
//     Route::prefix('catagory')->name('catagory.')->group(function () {
//         Route::get('/', [CategoryController::class, 'index'])->name('index');
//         Route::get('/create', [CategoryController::class, 'create'])->name('create');
//         Route::post('/', [CategoryController::class, 'store'])->name('store');
//         Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
//         Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
//         Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
//         Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
//     });
// });