<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories =Categories::all();
            return Response($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found. ' ],$e, 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'string|required',
                'description' => 'string|required',
            ]);
            $catetgory=Categories::create($request->all());
            return Response($catetgory, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create the resource.'], $e, 500);
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category =Categories::findOrFail($id);
            return Response($category, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.', ] ,$e, 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
        $category= Categories::findOrFail($id);
        $category->update($request->all());
        return Response($category,200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the resource.'], $e, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        Categories::destroy($id);
        return Response(null,200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the resource.'],  $e, 500);
        }
    }
}
