<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutletUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|min:3',
            'description' => 'sometimes|string|max:255',
            'price' => 'sometimes|decimal:0,2',
            'stock' => 'integer',
            'image' => 'url',
            'has_discount'=>'sometimes|boolean',
            'discount'=> 'sometimes|numeric',
            'category_id'=>'sometimes|integer|exists:categories,id',
            'provider_id'=>'sometimes|integer|exists:providers,id'
        ];
    }
}
