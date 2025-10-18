<?php

use App\Http\Controllers\APIAuthController;
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
Route::post('/logout', [APIAuthController::class, 'logout']);

//Transactions
Route::get('/product/list', [APITransactionController::class, 'show_products']);
Route::get('/carts/list/{user_id}', [APITransactionController::class, 'show_carts']);