<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'categories_id',
        'user_id'
    ];

    public function category()
    {
        return $this->belongsTo(categories::class);
    }


    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
