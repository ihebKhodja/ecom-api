<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'categories_id',
        'users_id'
    ];

    public function category()
    {
        return $this->belongsTo(categories::class);
    }
     public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    
}
