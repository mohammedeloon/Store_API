<?php

use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Buyers
 */
Route::apiResource('buyers' , BuyerController::class , ['only' => ['index' , 'show']]);

/**
 * Sellers
 */
Route::apiResource('sellers' , SellerController::class , ['only' => ['index' , 'show']]);

/**
 * Users
 */
Route::apiResource('users' , UserController::class);

/**
 * Categories
 */
Route::apiResource('categories' , CategoryController::class);

/**
 * Products
 */
Route::apiResource('products' , ProductController::class , ['only' => ['index' , 'show']]);

/**
 * Transactions
 */
Route::apiResource('transactions' , TransactionController::class , ['only' => ['index' , 'show']]);

 