<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/guest', [AuthController::class, 'guest']);
});

Route::middleware('auth')->group(function () {
    Route::get('/home', HomeController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/users')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/search', [UserController::class, 'search']);

        Route::get('/create', [UserController::class, 'create']);
        Route::post('/store', [UserController::class, 'store']);

        Route::get('/edit/{id}', [UserController::class, 'edit']);
        Route::put('/update', [UserController::class, 'update']);

        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });

    Route::middleware('role:gudang')->group(function () {
        Route::prefix('/kategori')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/search', [CategoryController::class, 'search']);

            Route::get('/create', [CategoryController::class, 'create']);
            Route::post('/store', [CategoryController::class, 'store']);

            Route::get('/edit/{id}', [CategoryController::class, 'edit']);
            Route::put('/update', [CategoryController::class, 'update']);

            Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
        });

        Route::prefix('/barang')->group(callback: function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/search', [ProductController::class, 'search']);
            Route::get('/create', [ProductController::class, 'create']);
            Route::post('/store', [ProductController::class, 'store']);
            Route::get('/edit/{id}', [ProductController::class, 'edit']);
            Route::put('/update', [ProductController::class, 'update']);
            Route::delete('/delete/{id}', [ProductController::class, 'destroy']);

            Route::get('detail/{id}', [ProductController::class, 'index_detail']);
            Route::get('detail/create/{id}', [ProductController::class, 'create_detail']);
            Route::post('detail/store', [ProductController::class, 'store_detail']);
            Route::get('detail/edit/{id}', [ProductController::class, 'edit_detail']);
            Route::put('detail/update', [ProductController::class, 'update_detail']);
            Route::delete('detail/delete/{id}', [ProductController::class, 'delete_detail']);

            Route::get('/refillStock/{id}', [ProductController::class, 'index_stock']);
            Route::get('/refillStock/{id}/filter', [ProductController::class, 'filter']);
            Route::get('/refillStock/create/{id}', [ProductController::class, 'add_stock']);
            Route::post('/refillStock/store', [ProductController::class, 'store_stock']);
        });

        Route::prefix('/satuan')->group(callback: function () {
            Route::get('/', [UnitController::class, 'index']);
            Route::get('/search', [UnitController::class, 'search']);

            Route::get('/create', [UnitController::class, 'create']);
            Route::post('/store', [UnitController::class, 'store']);

            Route::get('/edit/{id}', [UnitController::class, 'edit']);
            Route::put('/update', [UnitController::class, 'update']);

            Route::delete('/delete/{id}', [UnitController::class, 'destroy']);
        });
    });

    Route::middleware('role:kasir')->group(function () {
        Route::prefix('/transaksi')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
            Route::get('/search', [TransactionController::class, 'search']);

            Route::post('/cart/store', [TransactionController::class, 'cartStore']);
            Route::delete('/cart/delete/{id}', [TransactionController::class, 'cartDelete']);

            Route::post('/proses', [TransactionController::class, 'transactionStore']);

            Route::get('/history', [TransactionController::class, 'history']);
            Route::get('/history/filter', [TransactionController::class, 'filter']);

            Route::get('/detail/{id}', [TransactionController::class, 'detail']);
            Route::get('detail/{id}/print', [TransactionController::class, 'print']);
        });

        Route::prefix('/kredit')->group(function () {
            Route::get('/', [CreditController::class, 'index']);
            Route::get('/edit/{id}', [CreditController::class, 'edit']);
            Route::put('/update', [CreditController::class, 'update']);

            Route::get('bayar/{id}', [CreditController::class, 'payment']);
            Route::post('bayar/store/{id}', [CreditController::class, 'pay']);
        });
    });
});
