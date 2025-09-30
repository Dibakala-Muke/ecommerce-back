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
    Route::get('/', [ProduitController::class, 'index']);       // Tous les produits
    Route::get('{id}', [ProduitController::class, 'show']);     // Détail d’un produit
    Route::post('/', [ProduitController::class, 'store']);      // Créer un produit
    Route::put('{id}', [ProduitController::class, 'update']);   // Modifier un produit
    Route::delete('{id}', [ProduitController::class, 'destroy']);// Supprimer un produit
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategorieController::class, 'index']);      // Liste des catégories
    Route::post('/', [CategorieController::class, 'store']);     // Créer une catégorie
    Route::get('{id}', [CategorieController::class, 'show']);    // Détail d’une catégorie
    Route::put('{id}', [CategorieController::class, 'update']);   // Modifier une catégorie
    Route::delete('{id}', [CategorieController::class, 'destroy']); // Supprimer une catégorie
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);                     // Afficher le panier
    Route::post('/guest', [CartController::class, 'createGuestCart']);     // Créer un panier invité
    Route::post('/add', [CartController::class, 'addItem']);               // Ajouter un item
    Route::delete('/remove', [CartController::class, 'removeItem']);       // Supprimer un item
    Route::delete('/clear/{cartId}', [CartController::class, 'clearCart']); // Vider le panier
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']); // Créer une commande
});

Route::prefix('messages')->group(function () {
    Route::get('/{userId}', [MessageController::class, 'index']); // Tous les messages d’un utilisateur
    Route::post('/', [MessageController::class, 'store']);        // Envoyer un message
});

