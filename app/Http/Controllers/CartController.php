<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request) {
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
    }

    public function createGuestCart() {
        $guestToken = Str::uuid()->toString();
        $cart = Cart::create(['guest_token' => $guestToken]);
        return response()->json(['guest_token' => $guestToken, 'cart' => $cart]);
    }

    public function addItem(Request $request) {
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

        return CartItem::create($data);
    }

    public function removeItem(Request $request) {
        $data = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $item = CartItem::where('cart_id', $data['cart_id'])
                        ->where('product_id', $data['product_id'])
                        ->first();

        if ($item) {
            $item->delete();
            return response()->json(['message' => 'Item removed']);
        }

        return response()->json(['message' => 'Item not found'], 404);
    }

    public function clearCart($cartId) {
        $cart = Cart::findOrFail($cartId);
        $cart->items()->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}
