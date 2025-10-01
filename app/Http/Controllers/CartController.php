<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request)
    {
        try {
            $clientId = $request->input('client_id');
            $guestToken = $request->input('guest_token');

            if ($clientId) {
                $cart = Cart::with('items.product')->firstOrCreate(['client_id' => $clientId]);
            } elseif ($guestToken) {
                $cart = Cart::with('items.product')->firstOrCreate(['guest_token' => $guestToken]);
            } else {
                return response()->json(['message' => 'No identifier provided'], 400);
            }

            return $cart->load('items.product');
        } catch (\Exception $e) {
        }
    }

    public function createGuestCart()
    {
        try {
            $guestToken = Str::uuid()->toString();
            $cart = Cart::create(['guest_token' => $guestToken]);
            return response()->json([
                'success' => true,
                'guest_token' => $guestToken,
                'cart' => $cart
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du token',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addItem(Request $request)
    {
        try {
            $data = $request->validate([
                'cart_id' => 'required|exists:carts,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $existingItem = CartItem::where('cart_id', $data['cart_id'])
                ->where('product_id', $data['product_id'])
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $data['quantity'];
                $existingItem->save();
                return $existingItem;
            }

            $product = CartItem::create($data);
            return response()->json([
                'success' => true,
                'message' => "Produit ajouté avec succès",
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeItem(Request $request)
    {
        try {
            $data = $request->validate([
                'cart_id' => 'required|exists:carts,id',
                'product_id' => 'required|exists:products,id'
            ]);

            $item = CartItem::where('cart_id', $data['cart_id'])
                ->where('product_id', $data['product_id'])
                ->first();

            $item->delete();
            return response()->json(['message' => 'Item removed']);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'Produit non trouvé',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function clearCart($cartId)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->items()->delete();
        return response()->json(['message' => 'Le est vidé avec succès']);
    }
}
