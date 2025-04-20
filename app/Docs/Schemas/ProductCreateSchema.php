<?php

namespace App\Docs\Schemas;
/**
 * @OA\Schema(
 *     schema="ProductCreate",
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
 *         enum={false},
 *         description="Debe ser false o equivalente (declined). El producto creado NO puede tener descuento inicialmente.",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="discount",
 *         type="number",
 *         description="Debe ser 0 si no hay descuento.",
 *         example=0
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

class ProductCreateSchema {}
