<?php

namespace App\Docs\Schemas;

/** 
 * @OA\Schema( 
 *      schema="NotFoundError", 
 *      type="object", 
 *      title="Recurso no encontrado", 
 *      @OA\Property( 
 *          property="message", 
 *          type="string", 
 *          example="Recurso no encontrado" 
 *      ) 
 * ) 
 */

class NotFoundError {}
