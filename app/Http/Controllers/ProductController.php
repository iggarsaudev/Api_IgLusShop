<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::where('has_discount',false)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
       
            $product = Product::create($request->all());
            return response()->json(['id' => $product->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
        return response()->json(
        ['message' => 'Producto no encontrado'],
        404
        );
        }
        return response()->json($product, 200);
            }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        $product = Product::find($id);
        if (!$product) {
        return response()->json(
        ['message' => 'Producto no encontrado'],
        404
        );
        }
      
        // Actualizamos los campos
        $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
        return response()->json(
        ['message' => 'Producto no encontrado'],
        404
        );
        }
        $product->delete();
        return response()->json(
        ['message' => 'Producto eliminado correctamente'],
        204
        );

    }
}
