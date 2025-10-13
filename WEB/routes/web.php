<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ExpiredController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\RefillStockController;
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
    Route::get('/', function(){ return view('index'); });
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/guest', [AuthController::class, 'guest']);
});

Route::middleware('auth')->group(function () {
    Route::get('/home', HomeController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/users')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/search', [UserController::class, 'search']);
        Route::post('/store', [UserController::class, 'store']);
        Route::put('/update', [UserController::class, 'update']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });

    Route::middleware('role:gudang')->group(function () {
        Route::prefix('/kategori')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/search', [CategoryController::class, 'search']);
            Route::post('/store', [CategoryController::class, 'store']);
            Route::put('/update', [CategoryController::class, 'update']);
            Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
        });

        Route::prefix('/barang')->group(callback: function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/search', [ProductController::class, 'search']);
            Route::post('/store', [ProductController::class, 'store']);
            Route::put('/update', [ProductController::class, 'update']);
            Route::delete('/delete/{id}', [ProductController::class, 'destroy']);

            Route::get('detail/{id}', [ProductDetailController::class, 'index']);
            Route::post('detail/store', [ProductDetailController::class, 'store']);
            Route::put('detail/update', [ProductDetailController::class, 'update']);
            Route::delete('detail/delete/{id}', [ProductDetailController::class, 'delete']);

            Route::get('/refillStock/{id}', [RefillStockController::class, 'index']);
            Route::get('/refillStock/{id}/filter', [RefillStockController::class, 'filter']);
            Route::post('/refillStock/store', [RefillStockController::class, 'store']);

            Route::get('/expired', [ExpiredController::class, 'index']);
            Route::get('/expired/search', [ExpiredController::class, 'search']);
            Route::post('/expired/delete', [ExpiredController::class, 'delete']);
            Route::get('/expired/history', [ExpiredController::class, 'history']);
            Route::get('/expired/history/filter', [ExpiredController::class, 'filter']);
        });

        Route::prefix('/satuan')->group(callback: function () {
            Route::get('/', [UnitController::class, 'index']);
            Route::get('/search', [UnitController::class, 'search']);
            Route::post('/store', [UnitController::class, 'store']);
            Route::put('/update', [UnitController::class, 'update']);
            Route::delete('/delete/{id}', [UnitController::class, 'destroy']);
        });
    });

    Route::middleware('role:kasir')->group(function () {
        Route::prefix('/transaksi')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
            Route::get('/search', [TransactionController::class, 'search']);

            Route::post('/cart/store', [TransactionController::class, 'cartStore']);
            Route::put('/cart/plus/{id}', [TransactionController::class, 'cartPlus']);
            Route::put('/cart/minus/{id}', [TransactionController::class, 'cartMinus']);
            Route::delete('/cart/delete/{id}', [TransactionController::class, 'cartDelete']);

            Route::post('/proses', [TransactionController::class, 'transactionStore']);

            Route::get('/history', [TransactionController::class, 'history']);
            Route::get('/history/filter', [TransactionController::class, 'filter']);

            Route::get('/detail/{id}', [TransactionController::class, 'detail']);
            Route::get('/detail/{id}/print', [TransactionController::class, 'print']);
        });

        Route::prefix('/kredit')->group(function () {
            Route::get('/', [CreditController::class, 'index']);
            Route::get('/search', [CreditController::class, 'search']);

            Route::put('/update', [CreditController::class, 'update']);

            Route::post('pay/store/{id}', [CreditController::class, 'pay']);

            Route::get('/history', [CreditController::class, 'history']);
            Route::get('/history/filter', [CreditController::class, 'filter']);
        });
    });
});
