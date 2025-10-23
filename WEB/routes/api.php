<?php

use App\Http\Controllers\APIAuthController;
use App\Http\Controllers\APIHomeController;
use App\Http\Controllers\APITransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Auth
Route::post('/login', [APIAuthController::class, 'login']);
Route::post('/register', [APIAuthController::class, 'register']);
Route::post('/logout', [APIAuthController::class, 'logout'])->middleware('auth:sanctum');

//Transactions
Route::get('/products/list', [APITransactionController::class, 'show_products'])->middleware('auth:sanctum');
Route::get('/carts/list', [APITransactionController::class, 'show_carts'])->middleware('auth:sanctum');
Route::post('/carts/store', [APITransactionController::class, 'cartStore'])->middleware('auth:sanctum');
Route::post('/carts/plus', [APITransactionController::class, 'cartPlus']);
Route::post('/carts/min', [APITransactionController::class, 'cartMinus']);
Route::post('/carts/delete', [APITransactionController::class, 'cartDelete']);
Route::post('/transaction/store', [APITransactionController::class, 'transactionStore'])->middleware('auth:sanctum');
Route::get('/transaction/detail', [APITransactionController::class, 'invoice'])->middleware('auth:sanctum');
Route::get('/transaction/print/{id}', [APITransactionController::class, 'print']);

//Dashboard
Route::get('/dashboard', APIHomeController::class);