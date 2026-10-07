<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function show($id) {
        $product = Product::find($id);

      if (!$product) {
         return response()->json([
            'message' => 'Product not found'
         ], 500);
      }

      return response()->json($product);
    }

    public function store(Request $request) {
        if ($request->user()->role !== "admin"){
            return response()->json([
            'message' => 'Unauthorized , only admin can create products'
        ], 403);
        }
        $validated=$request->validate([
            "title" =>"required|string|max:255",
            "price"=>"required|numeric",
            "description"=>"nullable|string",
            "category"=>"required|in:men,women,fragrances,accessories",
            "image" =>"required|string|max:255",
        ]);
         $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update(Request $request, $id) {
        if ($request->user()->role !== "admin"){
            return response()->json([
            'message' => 'Unauthorized , only admin can update products'
        ], 403);
        }
        $product=Product::find($id);

        if (!$product) {
         return response()->json([
            'message' => 'Product not found'
         ], 500);
         }
         $product->update([

             "title" =>$request->title ,
             "price"=>$request->price ,
             "description"=>$request-> description,
             "category"=>$request-> category,
             "image"=>$request->image 
             ]);
    }

    public function destroy(Request $request, $id) {
        if ($request->user()->role !== "admin"){
            return response()->json([
            'message' => 'Unauthorized , only admin can delete products'
        ], 403);
        }
        $product=Product::find($id);

        if (!$product) {
         return response()->json([
            'message' => 'Product not found'
         ], 500);
         }
          $product->delete();

      return response()->json([
         'message' => 'Product deleted successfully'
      ]);
    }
}
