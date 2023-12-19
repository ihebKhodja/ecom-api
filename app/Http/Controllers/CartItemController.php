<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $carts =CartItem::all();
            return Response($carts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

   
    /**
     * Display the specified resource.
     */

      public function addToCart(Request $request, string $productId)
    {
        try{ 

        $cartitem=CartItem::create([
            'users_id' => auth()->id(),
            'products_id' => $productId,
            'quantity' => $request->input('quantity', 1),
        ]);
        return response($cartitem, 201);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    
/** 
 * Sending Cart Item with product 
*/
    public function showByUser()
    {
        try {   
            $userId=auth()->id();
            $cartItems = CartItem::where('users_id', auth()->id())->get();
            if ($cartItems ->isEmpty()) {
                return response()->json(['message' => 'User has no cart items.'], 404);
            }
        
            $cartItemsProducts=$cartItems->map(function($cartItem){
                $cartItem->product =Product::find($cartItem->products_id);
                unset($cartItem->products_id);
                return $cartItem;
            });
            return Response($cartItemsProducts, 200);   
        
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'],$e, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
           
            $cartItem=CartItem::find($id);
            if ($quantity !== null) {
                $cartItem->update(['quantity' => $request->input('quantity')]);
                return response()->json($cartItem, 200);
            } else {
                return response()->json(['message' => 'Quantity is required.'], 400);
            }
        }catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], $e, 404);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            CartItem::destroy($id);
            return Response(null, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the resource.'], $e, 500);
        }
    }
}
