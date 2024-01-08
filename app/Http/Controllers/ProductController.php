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
                $products = Product::latest()->get();


                // Retrieve all media for each product
                foreach ($products as $product) {
                    $media = $product->getFirstMediaUrl('images');
                    $media=preg_replace('#^https?://http://#i', 'http://', $media); // there is a duplication of http in the url
                    $product->image=$media;
                    unset($product->media);
                }

                return response()->json($products, 200);
        
            } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], $e, 404);
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

            if($request->has('image')){
                $product = Product::create([
                'name' => $request['name'],
                'slug' => $request['slug'],
                'price' => $request['price'],
                'description' => $request['description'],
                'categories_id' => $request['categories_id'],
                'user_id' => auth()->id(),
                // 'image' =>$request['image'],
            ]);
                $product->addMediaFromRequest('image')->usingName($product->name)->toMediaCollection('images'); 
            return response($product, 201);
            
              
            }else{
                $response["status"] = "failed";
                $response["message"] = "Failed! image(s) not uploaded";
                return response()->json($response);

            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create the resource.'], $e , 500);
        }
        
    }
        
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product=Product::find($id);
            
            $media = $product->getFirstMediaUrl('images');
            $media=preg_replace('#^https?://http://#i', 'http://', $media); // there is a duplication of http in the url
            $product->image=$media;
            unset($product->media);
            return Response($product, 200);
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
            $product=Product::find($id);
            $product->update(['name' => $request['name'],
                'slug' => $request['slug'],
                'price' => $request['price'],
                'description' => $request['description'],
                'categories_id' => $request['categories_id'],
                'user_id' => auth()->id(),
                // 'image' =>$request['image'],
            ]);
            // if($request->has('image')){
            //         $product->clearMediaCollection('images');

            //         $product->addMediaFromRequest('image')->usingName($product->name)->toMediaCollection('images'); 
            // }

                    $media = $product->getFirstMediaUrl('images');
                    $media=preg_replace('#^https?://http://#i', 'http://', $media); // there is a duplication of http in the url
                    $product->image=$media;
                    unset($product->media);
            
            return Response($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the resource.'],$e, 500);

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
            return response()->json(['error' => 'Resource not found.'],$e, 404);
        }
    }


    // show products by their category
    public function showByCategory(string $id)
    {
        try {
            $products =Product::where('categories_id', $id)->get();

             foreach ($products as $product) {
                    $media = $product->getFirstMediaUrl('images');
                    $media=preg_replace('#^https?://http://#i', 'http://', $media); // there is a duplication of http in the url
                    $product->image=$media;
                    unset($product->media);

            }

            return Response($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'],$e, 404);
        }

    }

    


}
