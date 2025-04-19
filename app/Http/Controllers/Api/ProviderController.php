<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Models\Provider;

class ProviderController extends Controller
{
    /**
     * Devuelve un listado de todos los proveedores registrados en 
     * la base de datos.
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Esta función obtiene todos los registros del modelo Provider y 
     * los devuelve en formato JSON. Cada proveedor incluye 
     * name y description.
     * 
     * @return \Illuminate\Http\JsonResponse Listado de proveedores 
     * 
     * @OA\Get( 
     *     path="/api/providers", 
     *     summary="Obtener todos los proveedores", 
     *     tags={"Providers"}, 
     *     security={{"bearerAuth": {}}},
     *     @OA\Response( 
     *         response=200, 
     *         description="Lista de proveedores", 
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Provider")
     *         ) 
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ) 
     * )
     */
    public function index()
    {
        return response()->json(Provider::all(), 200);
    }

    /**
     * Crea un nuevo proveedor con los datos proporcionados. 
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Valida los campos requeridos antes de almacenar el recurso. 
     * El campo "description" es opcional. 
     * Devuelve el ID del proveedor creado y mensaje de confirmación si la operación es exitosa.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse ID del proveedor creado y mensaje de confirmación
     *
     * @OA\Post(
     *     path="/api/providers",
     *     summary="Crear un nuevo proveedor",
     *     tags={"Providers"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProviderCreateSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Proveedor creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Proveedor creado correctamente."),
     *             @OA\Property(property="id", type="integer", example=1)
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
    public function store(StoreProviderRequest $request)
    {
        $provider = Provider::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Proveedor creado correctamente.',
            'id' => $provider->id,
        ], 201);
    }

    /** 
     * Devuelve los datos de un proveedor específico.
     * 
     * Requiere autenticación y permisos de administrador. 
     * 
     * Busca el proveedor por su ID. Si no se encuentra, devuelve un error 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Detalles del proveedor o error
     * 
     * @OA\Get( 
     *      path="/api/providers/{id}", 
     *      summary="Obtener un proveedor por ID", 
     *      tags={"Providers"}, 
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del proveedor", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Detalles del proveedor", 
     *          @OA\JsonContent(ref="#/components/schemas/Provider") 
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *      @OA\Response( 
     *          response=404, 
     *          description="Recurso no encontrado", 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/NotFoundError" 
     *          ) 
     *      ) 
     * ) 
     */
    public function show(string $id)
    {
        $provider = Provider::find($id);

        if (!$provider) {
            return response()->json(['error' => 'Recurso no encontrado'], 404);
        }

        return response()->json($provider);
    }

    /** 
     * Actualiza los datos de un proveedor existente.
     * 
     * Requiere autenticación y permisos de administrador.  
     * 
     * Permite actualizar uno o varios campos del proveedor. 
     * Devuelve el proveedor actualizado si todo es correcto. 
     * 
     * @param \Illuminate\Http\Request $request 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Proveedor actualizado o error 
     * 
     * @OA\Put( 
     *      path="/api/providers/{id}", 
     *      summary="Actualizar un proveedor", 
     *      tags={"Providers"}, 
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del proveedor a actualizar", 
     *          @OA\Schema(type="integer") 
     *      ), @OA\RequestBody( 
     *          required=true, 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/ProviderUpdateSchema" 
     *          ) 
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Proveedor actualizado correctamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Proveedor actualizado correctamente"),
     *              @OA\Property(property="provider", ref="#/components/schemas/Provider")
     *          )
     *      ), 
     *      @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ), 
     *     @OA\Response( 
     *          response=404, 
     *          description="Recurso no encontrado", 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/NotFoundError" 
     *          ) 
     *      ), 
     *      @OA\Response( 
     *          response=422, 
     *          description="Errores de validación", 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/ValidationError" 
     *          ) 
     *      ) 
     * ) 
     */
    public function update(UpdateProviderRequest $request, $id)
    {
        $provider = Provider::find($id);

        if ($request->has('name')) $provider->name = $request->name;
        if ($request->has('description')) $provider->description = $request->description;

        $provider->save();

        return response()->json([
            'message' => 'Proveedor actualizado correctamente.',
            'provider' => $provider,
        ], 200);
    }

    /** 
     * Elimina un proveedor por su ID. 
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Si el proveedor no existe, devuelve un error 404. 
     * Si se elimina correctamente, devuelve un código 204. 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Resultado de la eliminación 
     * 
     * @OA\Delete( 
     *      path="/api/providers/{id}", 
     *      summary="Elimina un proveedor", 
     *      tags={"Providers"},
     *      security={{"bearerAuth": {}}}, 
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del proveedor a eliminar", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Proveedor eliminado correctamente" 
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
     *          description="Recurso no encontrado", 
     *          @OA\JsonContent( ref="#/components/schemas/NotFoundError" )
     *      ), 
     * ) 
     */
    public function destroy(string $id)
    {
        $provider = Provider::find($id);

        if (!$provider) {
            throw new ResourceNotFoundException('Recurso no encontrado');
        }

        $provider->delete();

        return response()->json([
            'message' => 'Proveedor eliminado.'
        ], 200);
    }
}
