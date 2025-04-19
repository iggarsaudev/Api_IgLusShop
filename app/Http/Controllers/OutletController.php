<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\OutletRequest;
use App\Http\Requests\OutletUpdateRequest;
class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::where('has_discount',true)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OutletRequest $request)
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
        if (!($product->has_discount)) {
            return response()->json(
            ['message' => 'Este producto no es del outlet'],
            404
            );
        }
        return response()->json($product, 200);
            
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OutletUpdateRequest $request, string $id)
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
        if (!($product->has_discount)) {
            // Este endpoint solo debe eliminar productos del oultet
            return response()->json(
            ['message' => 'Este producto no es del outlet'],
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
