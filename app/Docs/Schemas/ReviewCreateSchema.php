<?php

namespace App\Docs\Schemas;
/**
 * @OA\Schema(
 *     schema="ReviewCreate",
 *     type="object",
 *     required={"product_id", "rating"},
 *     @OA\Property( 
 *          property="product_id", 
 *          type="integer", 
 *          example=1,
 *          description="Id del producto al que apunta la reseña (Clave foránea a products)" 
 *      ),
 *     @OA\Property( 
 *          property="comment", 
 *          type="string", 
 *          example="El producto es de buena calidad",
 *      ), 
 *     @OA\Property( 
 *          property="rating", 
 *          type="integer", 
 *          example=5,
 *      ),
 * )
 */

class ReviewCreateSchema {}