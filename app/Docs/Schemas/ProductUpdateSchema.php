<?php

namespace App\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="ProductUpdate",
 *     type="object",
 *     title="Actualización de Producto",
 *     description="Schema para actualizar un producto",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         minLength=3,
 *         example="Camiseta Deportiva"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         maxLength=255,
 *         example="Camiseta cómoda para entrenamiento"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         example=49.99
 *     ),
 *     @OA\Property(
 *         property="stock",
 *         type="integer",
 *         example=150
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="uri",
 *         example="https://example.com/images/producto.png"
 *     ),
 *     @OA\Property(
 *         property="has_discount",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="discount",
 *         type="number",
 *         example=0
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         example=1,
 *         description="ID de la categoría relacionada (clave foránea a Category)" 
 *     ),
 *     @OA\Property(
 *         property="provider_id",
 *         type="integer",
 *         example=2,
 *         description="ID del proveedor relacionada (clave foránea a Provider)"
 *     )
 * )
 */

class ProductUpdateSchema {}
