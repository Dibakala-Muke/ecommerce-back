<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index() {
        return Produit::all();
    }

    public function show($id) {
        return Produit::findOrFail($id);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'size' => 'required',
            'color' => 'required',
            'brand' => 'required',
            'gender' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string'
        ]);

        return Produit::create($data);
    }

    public function update(Request $request, $id) {
        $product = Produit::findOrFail($id);
        $product->update($request->all());
        return $product;
    }

    public function destroy($id) {
        return Produit::destroy($id);
    }

}
