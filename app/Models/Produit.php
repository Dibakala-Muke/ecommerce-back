<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'quantity', 'size', 'color', 'brand', 'gender', 'category_id', 'image'];

    public function category() {
        return $this->belongsTo(Categorie::class);
    }
}
