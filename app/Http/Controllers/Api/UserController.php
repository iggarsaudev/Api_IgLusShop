<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Devuelve un listado de todos los usuarios registrados en 
     * la base de datos.
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Esta función obtiene todos los registros del modelo User y 
     * los devuelve en formato JSON. Cada usuario incluye 
     * name, email, password y role.
     * 
     * @return \Illuminate\Http\JsonResponse Listado de usuarios 
     * 
     * @OA\Get( 
     *     path="/api/users", 
     *     summary="Obtener todos los usuarios", 
     *     tags={"Users"}, 
     *     security={{"bearerAuth": {}}},
     *     @OA\Response( 
     *         response=200, 
     *         description="Lista de usuarios", 
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
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
        return response()->json(User::all());
    }

    /**
     * Crea un nuevo usuario con los datos proporcionados. 
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Valida los campos requeridos antes de almacenar el recurso. 
     * El campo "role" es opcional, en el caso de no indicarlo será "user". 
     * Devuelve el ID del usuario creado y mensaje de confirmación si la operación es exitosa.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse ID del usuario creado y mensaje de confirmación
     *
     * @OA\Post(
     *     path="/api/users",
     *     summary="Crear un nuevo usuario",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreateSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario creado correctamente."),
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
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user', // Asigna el rol enviado o 'user' por defecto
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'id' => $user->id,
        ], 201);
    }

    /** 
     * Devuelve los datos de un usuario específico.
     * 
     * Requiere autenticación y permisos de administrador. 
     * 
     * Busca el usuario por su ID. Si no se encuentra, devuelve un error 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Detalles del usuario o error
     * 
     * @OA\Get( 
     *      path="/api/users/{id}", 
     *      summary="Obtener un usuario por ID", 
     *      tags={"Users"}, 
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del usuario", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Detalles del usuario", 
     *          @OA\JsonContent(ref="#/components/schemas/User") 
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
     *          description="Usuario no encontrado", 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/NotFoundError" 
     *          ) 
     *      ) 
     * ) 
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    /** 
     * Actualiza los datos de un usuario existente.
     * 
     * Requiere autenticación y permisos de administrador.  
     * 
     * Permite actualizar uno o varios campos del usuario. 
     * Devuelve el usuario actualizado si todo es correcto. 
     * 
     * @param \Illuminate\Http\Request $request 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Usuario actualizado o error 
     * 
     * @OA\Put( 
     *      path="/api/users/{id}", 
     *      summary="Actualizar un usuario", 
     *      tags={"Users"}, 
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del usuario a actualizar", 
     *          @OA\Schema(type="integer") 
     *      ), @OA\RequestBody( 
     *          required=true, 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/UserUpdateSchema" 
     *          ) 
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Usuario actualizado correctamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Usuario actualizado correctamente"),
     *              @OA\Property(property="user", ref="#/components/schemas/User")
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
     *          description="Usuario no encontrado", 
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
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);
        if ($request->has('role') && Auth::user()->role === 'admin') {
            $user->role = $request->role;
        }

        $user->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'user' => $user,
        ], 200);
    }

    /** 
     * Elimina un usuario por su ID. 
     * 
     * Requiere autenticación y permisos de administrador.
     * 
     * Si el usuario no existe, devuelve un error 404. 
     * Si se elimina correctamente, devuelve un código 204. 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Resultado de la eliminación 
     * 
     * @OA\Delete( 
     *      path="/api/users/{id}", 
     *      summary="Elimina un usuario", 
     *      tags={"Users"},
     *      security={{"bearerAuth": {}}}, 
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID del usuario a eliminar", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Usuario eliminado correctamente" 
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
     *          description="Usuario no encontrado", 
     *          @OA\JsonContent( ref="#/components/schemas/NotFoundError" )
     *      ), 
     * ) 
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException('Usuario no encontrado');
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado.'
        ], 200);
    }
}
