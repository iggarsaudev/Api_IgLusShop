<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OutletRequest;

class OutletController extends Controller
{
    /**
     * Devuelve un listado de todos los productos que tienen descuento.
     * 
     * Este endpoint es público.
     * 
     * Esta función obtiene todos  los productos que tienen descuento del 
     * modelo Product y los devuelve en formato JSON. Cada producto incluye 
     * id, name, description, price, stock, image,has_discount,discount,provider_id,category_id,created_at,updated_at.
     * 
     * @return \Illuminate\Http\JsonResponse Listado de productos sin descuento 
     * 
     * @OA\Get( 
     *     path="/api/outlet", 
     *     summary="Obtener todos los productos sin descuento", 
     *     tags={"Outlet"}, 
     *     @OA\Response( 
     *         response=200, 
     *         description="Lista de productos sin descuento (endpoint público, no requiere autenticación)", 
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         ) 
     *     ),
     * )
     */
    public function index()
    {
        return Product::where('has_discount', true)->get();
    }

    /**
     * Crea un nuevo producto con los datos proporcionados. 
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Al crear un nuevo producto, se deben proporcionar los siguientes campos obligatorios:
     * 
     * - `name`: El nombre del producto, que debe ser una cadena de texto con un mínimo de 3 caracteres.
     * - `description`: Descripción detallada del producto, la cual debe ser una cadena de texto con un máximo de 255 caracteres.
     * - `price`: El precio del producto, que debe ser un número decimal con un máximo de dos decimales.
     * - `category_id`: ID de la categoría a la que pertenece el producto. Debe ser un número entero y debe existir en la base de datos.
     * - `provider_id`: ID del proveedor asociado al producto. Debe ser un número entero y debe existir en la base de datos.
     * 
     * Los siguientes campos son opcionales, pero si se incluyen, deben cumplir con las siguientes validaciones:
     * 
     * - `stock`: Número entero que indica la cantidad disponible del producto en stock.
     * - `image`: URL válida que apunta a la imagen del producto.
     * - `has_discount`: Campo booleano que indica si el producto tiene descuento. Este campo debe ser siempre verdarep para los productos del outlet.
     *    Si se quiere generar un producto sin descuento se debe hacer desde el endpoint correspondiente de products.
     * - `discount`: El descuento aplicado al producto. Si `has_discount` es verdadero, este valor debe ser mayor de 0 y menor de 100.
     * 
     * La validación incluye restricciones de tipo de datos y valores específicos. Si cualquier campo no cumple con las reglas, se devolverá un error.
     *
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse ID del producto creado y mensaje de confirmación
     *
     * @OA\Post(
     *     path="/api/outlet",
     *     summary="Crear un nuevo producto del outlet",
     *     tags={"Outlet"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OutletCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto creado correctamente."),
     *             @OA\Property(property="id", type="integer", example=21)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function store(OutletRequest $request)
    {

        $product = Product::create($request->all());
        return response()->json([
            'message' => "Producto creado correctamente.",
            'id' => $product->id
        ], 201);
    }
    /** 
     * Devuelve los datos de un producto del outlet específico.
     * 
     * Es un endpoint público 
     * 
     * Busca el producto por su ID y comprueba que pertenezca al outlet.
     * Si no se encuentra, devuelve un error 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Detalles del producto o error
     * 
     * @OA\Get( 
     *      path="/api/outlet/{id}", 
     *      summary="Obtener un producto del outlet por ID", 
     *      tags={"Outlet"}, 
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del producto", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Detalles del producto", 
     *          @OA\JsonContent(ref="#/components/schemas/Product") 
     *      ),
     *      @OA\Response( 
     *          response=404, 
     *          description="Producto no encontrado o perteneciente al outlet", 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/NotFoundError" 
     *          ) 
     *      ) 
     * ) 
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
     * Elimina un producto que pertenezca al outlet por su ID. 
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Si el producto no existe, devuelve un error 404. 
     * Si se elimina correctamente, devuelve un código 204. 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Resultado de la eliminación 
     * 
     * @OA\Delete( 
     *      path="/api/outlet/{id}", 
     *      summary="Elimina un producto del outlet", 
     *      tags={"Outlet"},
     *      security={{"bearerAuth": {}}}, 
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del producto a eliminar", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Producto eliminado correctamente" 
     *      ), 
     *      @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *      ),
     *      @OA\Response( 
     *          response=404, 
     *          description="Producto no encontrado", 
     *          @OA\JsonContent( ref="#/components/schemas/NotFoundError" )
     *      ), 
     * ) 
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
            200
        );
    }
}
