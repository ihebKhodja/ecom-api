<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $carts =Cart::all();
            return Response($carts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        $request->validate([
            'user_id' => 'required|string',
                'products_id'=>'required|string',
            ]);
            $cart=Cart::create($request->all());
            return Response($cart, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create the resource.'], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            return Response($cart, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * Adding product to cart
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Delete a product from a cart
     */
    public function destroy(string $id)
    {
        //
    }
}
