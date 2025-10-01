<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProduitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [CartController::class, 'index']);
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProduitController::class, 'index']);
    Route::get('{id}', [ProduitController::class, 'show']);
    Route::post('/', [ProduitController::class, 'store']);
    Route::put('{id}', [ProduitController::class, 'update']);
    Route::delete('{id}', [ProduitController::class, 'destroy']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategorieController::class, 'index']);
    Route::post('/', [CategorieController::class, 'store']);
    Route::get('{id}', [CategorieController::class, 'show']);
    Route::put('{id}', [CategorieController::class, 'update']);
    Route::delete('{id}', [CategorieController::class, 'destroy']);
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/guest', [CartController::class, 'createGuestCart']);
    Route::post('/add', [CartController::class, 'addItem']);
    Route::delete('/remove', [CartController::class, 'removeItem']);
    Route::delete('/clear/{cartId}', [CartController::class, 'clearCart']);
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
});

Route::prefix('messages')->group(function () {
    Route::get('/{userId}', [MessageController::class, 'index']);
    Route::post('/', [MessageController::class, 'store']);
});

