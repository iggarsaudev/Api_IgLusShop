<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Recurso no encontrado'
        ], 404);
    }
}
