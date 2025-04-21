<?php

namespace App\Docs\Schemas;

/** 
 * @OA\Schema( 
 *      schema="Review", 
 *      type="object", 
 *      title="Review", 
 *      required={"id","user_id","product_id","comment","rating"}, 
 *      @OA\Property(property="id", type="integer", example=1), 
 *      @OA\Property( 
 *          property="user_id", 
 *          type="integer", 
 *          example=1,
 *          description="Id del usuario que crea la reseña (Clave foránea a users)" 
 *      ), 
 *      @OA\Property( 
 *          property="product_id", 
 *          type="integer", 
 *          example=1,
 *          description="Id del producto al que apunta la reseña (Clave foránea a products)" 
 *      ),
 *      @OA\Property( 
 *          property="comment", 
 *          type="string", 
 *          example="El producto es de buena calidad",
 *      ), 
 *      @OA\Property( 
 *          property="rating", 
 *          type="integer", 
 *          example=5,
 *      ), 
 *      @OA\Property( 
 *      property="created_at", 
 *      type="string", 
 *      format="date-time", 
 *      example="2025-04-05 21:45:59" 
 *      ), 
 *      @OA\Property( 
 *          property="updated_at", 
 *          type="string", 
 *          format="date-time", 
 *          example="2025-04-05 21:45:59" 
 *      ) 
 * ) 
 */

class ReviewSchema {}