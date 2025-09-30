<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Categorie::query()->where('actif', true);

            if ($request->has('name') && $request->input('name') !== null) {
                $name = $request->query('name');
                $query = $query->where('name', 'like', "%$name%");
            }

            return CategorieResource::collection($query->get());
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'code' => 500,
                'message' => 'Erreur interne du serveur'
            ]);
        }
    }

    public function store(BaseRequest $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:categories,slug',
                'actif' => 'boolean'
            ]);

            $category = Categorie::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès.',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Categorie::query()
                ->findOrFail($id);
            $category = new CategorieResource($category);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie récupérée avec succès.',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'sometimes|required',
                'slug' => 'sometimes|required|unique:categories,slug,' . $id,
                'actif' => 'sometimes|boolean'
            ]);

            $category = Categorie::findOrFail($id);
            $category = $category->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Catégorie mise à jour avec succès.',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Categorie::destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
