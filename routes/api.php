<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route::resource('/products',ProductController::class);

/// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix'=>'products'], function(){
    Route::get('/search/{name}', [ProductController::class, 'search']); 
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/categories/{id}', [ProductController::class, 'showByCategory']);
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoriesController::class,'index']);
    Route::get('/{id}', [CategoriesController::class, 'show']);
});

Route::get('/carts', [CartController::class, 'index']);

// Protected routes
Route::group(['middleware'=>['auth:sanctum']], function(){

    Route::prefix('/products')->group(function(){
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::post('/', [CategoriesController::class, 'store']);
        Route::put('/{id}', [CategoriesController::class, 'update']);
        Route::delete('/{id}', [CategoriesController::class, 'destroy']);


    });
    Route::group(['prefix' => 'carts'], function () {
        Route::post('/carts', [CartController::class, 'store']);
        Route::get('/carts/{id}', [CartController::class, 'show']);
    });
    

    Route::post('/logout',[AuthController::class,'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
