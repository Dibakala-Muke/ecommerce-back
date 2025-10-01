<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        try {
            $products = Produit::with('category')->paginate(9);
            return ProduitResource::collection($products);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                "code" => 500,
                "message" => "Erreur interne du serveur"
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
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
            $product = Produit::create($request->all());
            return response()->json([
                "succes" => true,
                "message" => "Produit créé avec succes.",
                "data" => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "succes" => false,
                "message" => "Erreur lors de la création du produit",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Produit::findOrFail($id);
            $product = new ProduitResource($product);

            return response()->json([
                "succes" => true,
                "message" => "Produit récupéré avec succes.",
                "data" => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "succes" => false,
                "message" => "Produit non trouvé",
                "error" => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'sometimes|required',
                'description' => 'sometimes|nullable',
                'price' => 'sometimes|required|numeric',
                'quantity' => 'sometimes|required|integer',
                'size' => 'sometimes|required',
                'color' => 'sometimes|required',
                'brand' => 'sometimes|required',
                'gender' => 'sometimes|required',
                'category_id' => 'sometimes|required|exists:categories,id',
                'image' => 'sometimes|nullable|string'
            ]);

            $product = Produit::findOrFail($id);
            $product = $product->update($request->all());
            return response()->json([
                "succes" => true,
                "message" => "Produit mis à jour avec succes",
                "data" => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "sucess" => false,
                "message" => "Erreur lors de la mis à jour du produit",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            Produit::destroy($id);

            return response()->json([
                "succes" => true,
                "message" => "Produit supprimé avec succes"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "succes" => false,
                "message" => "Erreur lors de la suppression du produit",
                "error" => $e->getMessage()
            ]);
        }
    }
}
