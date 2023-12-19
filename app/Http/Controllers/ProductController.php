<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Product;
use Kreait\Firebase\Messaging\Message;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products= Product::latest()->get();
            return Response($products,200);

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
                'name'=>'required',
                'slug'=>'required',
                'price' => 'required',
                'description'=>'required',
                'categories_id'=>'required',
                'image' => 'required',
                'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $image = $request->file('image');

            $filename= time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/images/', $filename);
        
            // Will try to use media library later on
            // $product->addMedia($image)->toMediaCollection('images'); 
            //  $image->addFromMediaLibraryRequest('image')->toMediaCollection('images');
            
            if($request->has('image')){
                $product = Product::create([
                'name' => $request['name'],
                'slug' => $request['slug'],
                'price' => $request['price'],
                'description' => $request['description'],
                'categories_id' => $request['categories_id'],
                'user_id' => auth()->id(),
                'image' =>$image,
                ]);
            return response($product, 201);
            
              
            }else{
                $response["status"] = "failed";
                $response["message"] = "Failed! image(s) not uploaded";
                return response()->json($response);

            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create the resource.', $e ], 500);
        }
        
    }
        
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product=Product::find($id);
            return Response($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            $product=Product::find($id);
            $product->update($request->all());
            return Response($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the resource.'], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Product::destroy($id);
            return Response(null, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the resource.'], $e, 500);
        }
    }
    
    

    // Search by product name
    public function search(string $name){
        try {
            $product =Product::where('name', 'like', '%' . $name . '%')->get();
            return Response($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    // show products by their category
    public function showByCategory(string $id)
    {
        try {
            $product =Product::where('categories_id', $id)->get();
            return Response($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }

    }


}
