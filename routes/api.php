<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


/// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix'=>'products'], function(){
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/search/{name}', [ProductController::class, 'search']); 
    Route::get('/categories/{id}', [ProductController::class, 'showByCategory']);
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoriesController::class,'index']);
    Route::get('/{id}', [CategoriesController::class, 'show']);
});

Route::get('/carts', [CartController::class, 'index']);

// Protected routes
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware'=>['auth:sanctum', 'is_admin']], function(){
    
    Route::prefix('/products')->group(function(){
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::post('/', [ProductController::class, 'store']);
         });

    Route::group(['prefix' => 'categories'], function () {
        Route::post('/', [CategoriesController::class, 'store']);
        Route::put('/{id}', [CategoriesController::class, 'update']);
        Route::delete('/{id}', [CategoriesController::class, 'destroy']);
    });

    
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware'=>['auth:sanctum']], function(){

   
    Route::group(['prefix' => 'cartitems'], function () {
        // Route::get('/', [CartItemController::class, 'index']);
        Route::get('/user', [CartItemController::class, 'showByUser']);
        
        Route::put('/{id}', [CartItemController::class, 'update']);
        Route::delete('/{id}', [CartItemController::class, 'destroy']);
        Route::post('/add/{id}', [CartItemController::class, 'addToCart']);
    });
        

    Route::post('/logout',[AuthController::class,'logout']);
    
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
