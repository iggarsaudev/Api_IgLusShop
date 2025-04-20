<?php

namespace App\Docs\Schemas;
/**
 * @OA\Schema(
 *     schema="OutletCreate",
 *     type="object",
 *     required={"name", "description", "price", "category_id", "provider_id"},
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
 *         example="Camiseta técnica de alto rendimiento"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         example=29.99
 *     ),
 *     @OA\Property(
 *         property="stock",
 *         type="integer",
 *         example=100
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="uri",
 *         example="https://example.com/product.jpg"
 *     ),
 *     @OA\Property(
 *         property="has_discount",
 *         type="boolean",
 *         enum={true},
 *         description="Debe ser verdadero o equivalente (accepted). El producto creado debe tener descuento",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="discount",
 *         type="number",
 *         description="Debe ser un valor mayor que 0 y menor o igual que 100.",
 *         example=15
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         description="ID de la categoría relacionada (clave foránea a Category)",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="provider_id",
 *         type="integer",
 *         description="ID del proveedor relacionada (clave foránea a Provider)",
 *         example=5
 *     )
 * )
 */

class OutletCreateSchema {}
