<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request) {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'shipping_address' => 'required|string',
            'items' => 'required|array'
        ]);

        $order = Order::create($data);

        foreach ($data['items'] as $item) {
            $order->items()->create($item);
        }

        return $order->load('items');
        }

}
