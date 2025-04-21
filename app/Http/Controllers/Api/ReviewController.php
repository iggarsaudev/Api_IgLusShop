<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Devuelve un listado de todas las reseñas de la base de datos.
     * 
     * 
     * Esta función obtiene todos los registros del modelo Review y 
     * los devuelve en formato JSON. 
     * 
     * @return \Illuminate\Http\JsonResponse Listado de reviews 
     * 
     * @OA\Get( 
     *     path="/api/reviews", 
     *     summary="Obtener todas las reviews", 
     *     tags={"Reviews"}, 
     *     @OA\Response( 
     *         response=200, 
     *         description="Lista de reviews", 
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Review")
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
        return response()->json(Review::all(), 200);
    }

    /**
     * Crea una nueva review con los datos proporcionados. 
     * 
     * Requiere autenticación
     * 
     * Valida los campos requeridos antes de almacenar el recurso. 
     * El campo "comment" es opcional. 
     * Devuelve el ID de la review creada y mensaje de confirmación si la operación es exitosa.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse ID de la review creada y mensaje de confirmación
     *
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Crear una nueva review",
     *     tags={"Reviews"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ReviewCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Review creada correctamente."),
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
    public function store(ReviewRequest $request)
    {
        $user_id=Auth::id();
        $review = Review::create([
            'user_id' => $user_id,
            'product_id' => $request->product_id,
            'comment'=> $request->comment,
            'rating'=> $request->rating
        ]);

        return response()->json([
            'message' => 'Review creada correctamente.',
            'id' => $review->id,
        ], 201);
    }

    /** 
     * Devuelve los datos de una review específica.
     * 
     * 
     * Busca la review por su ID. Si no se encuentra, devuelve un error 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Detalles de la review o error
     * 
     * @OA\Get( 
     *      path="/api/reviews/{id}", 
     *      summary="Obtener una review por su ID", 
     *      tags={"Reviews"}, 
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID de la review", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Detalles de la review", 
     *          @OA\JsonContent(ref="#/components/schemas/Review") 
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
        $review = Review::find($id);

        if (!$review) {
            throw new ResourceNotFoundException();
        }

        return response()->json($review);
    }

    /** 
     * Actualiza los datos de una review existente.
     * 
     * Requiere autenticación.
     * Solo se puede actualizar si la review ha sido creada por el usuario autenticado
     * Permite actualizar uno o varios campos de una review. 
     * Devuelve la review actualizada si todo es correcto. 
     * 
     * @param \Illuminate\Http\Request $request 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Review actualizada o error 
     * 
     * @OA\Put( 
     *      path="/api/reviews/{id}", 
     *      summary="Actualizar una review", 
     *      tags={"Reviews"}, 
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID de la review a actualizar", 
     *          @OA\Schema(type="integer") 
     *      ), @OA\RequestBody( 
     *          required=true, 
     *          @OA\JsonContent( 
     *              ref="#/components/schemas/ReviewUpdate" 
     *          ) 
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Review actualizada correctamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Review actualizada correctamente"),
     *              @OA\Property(property="review", ref="#/components/schemas/Review")
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
    public function update(UpdateReviewRequest $request, $id)
    {
        $review = Review::find($id);
        if (!$review) {
            throw new ResourceNotFoundException();
            }
            $user_id=Auth::id();

        if ($user_id!==$review->user_id) {
            return response()->json([
                'message' => 'No autorizado.'
            ], 403);
        }
      
        $review->update($request->all());

        return response()->json([
            'message' => 'Review actualizada correctamente.',
            'review' => $review,
        ], 200);
    }

    /** 
     * Elimina una review por su ID. 
     * 
     * Requiere autenticación.
     * 
     * Solo se puede eliminar si la review ha sido creada por el usuario autenticado
     * 
     * Si la review no existe, devuelve un error 404. 
     * Si se elimina correctamente, devuelve un código 204. 
     * 
     * @param string $id 
     * @return \Illuminate\Http\JsonResponse Resultado de la eliminación 
     * 
     * @OA\Delete( 
     *      path="/api/reviews/{id}", 
     *      summary="Elimina una review", 
     *      tags={"Reviews"},
     *      security={{"bearerAuth": {}}}, 
     *      @OA\Parameter( 
     *          name="id", 
     *          in="path", 
     *          required=true, 
     *          description="ID de la review a eliminar", 
     *          @OA\Schema(type="integer") 
     *      ), 
     *      @OA\Response( 
     *          response=200, 
     *          description="Review eliminado correctamente" 
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
        $review = Review::find($id);
        $user_id=Auth::id();

        if (!$review) {
            throw new ResourceNotFoundException();
        }
        
        if ($user_id!==$review->user_id) {
            return response()->json([
                'message' => 'No autorizado.'
            ], 403);
        }
        $review->delete();

        return response()->json([
            'message' => 'Review eliminada.'
        ], 200);
    }
}
