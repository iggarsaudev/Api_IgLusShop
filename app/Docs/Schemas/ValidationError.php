<?php

namespace App\Docs\Schemas;

/** 
 * @OA\Schema( 
 *      schema="ValidationError", 
 *      type="object", 
 *      title="Error de validación", 
 *      @OA\Property( 
 *          property="message", 
 *          type="string", 
 *          example="The given data was invalid." 
 *      ), 
 *      @OA\Property( 
 *          property="errors", 
 *          type="object", 
 *          @OA\Property( 
 *              property="titulo", 
 *              type="array", 
 *              @OA\Items( 
 *                  type="string", 
 *                  example="El nombre es obligatorio." 
 *              ) 
 *          ) 
 *      ) 
 * ) 
 */

class ValidationError {}
