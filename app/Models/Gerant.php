<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'gender', 'photo', 'phone', 'address'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
