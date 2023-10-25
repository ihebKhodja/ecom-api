<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [''];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

