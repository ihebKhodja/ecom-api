<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\Product;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
            $orders =Order::all();
            return Response($orders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], $e, 404);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try{
            $request->validate([
                'total'=>'required'
            ]);

        $userId=auth()->id();
        $order= Order::create([
            'users_id'=>$userId,
            'total'=>$request->total,
            'adress'=>$request->adress,
            'province'=>$request->province,
            'mobile'=>$request->mobile,
            'city'=>$request->city,
            'zipcode'=>$request->zipcode,
        ]);
        
        $cartItems = CartItem::where('users_id', auth()->id())->get();
        foreach ($cartItems as $cartItem) {
            $cartItem->orders_id =$order->id;
        }
         $cartItemsProducts=$cartItems->map(function($cartItem){
                $product =Product::find($cartItem->products_id);
                $cartItem->product= $product;
                unset($cartItem->products_id);
                return $cartItem;
            });

            return response()->json(['cartItems' => $cartItems], 200);

        }catch (\Exception $e) {
            return response()->json(['error' => $e], 404);
        }    
    }

    /**
     * Display the specified resource.
     */
    public function showByUser()
    {
        try {
             $userId=auth()->id();
            $orders = Order::where('users_id', auth()->id())->get();
            if ($orders ->isEmpty()) {
                return response()->json(['message' => 'User has no cart items.'], 404);
            }
            return response()->json($orders, 200);
        

            
         } catch (\Exception $e) {
            return response()->json(['error' => $e], 404);
         }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
