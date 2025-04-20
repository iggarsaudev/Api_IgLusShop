<?php

namespace App\Http\Requests;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderRequest extends FormRequest
{
    protected ?Provider $providerToUpdate = null;

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $id = $this->route('provider');

        $this->providerToUpdate = Provider::find($id);

        if (!$this->providerToUpdate) {
            throw new ResourceNotFoundException('Recurso no encontrado');
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|string|'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de caracteres',
            'name.max' => 'Máximo 255 caracteres',
            'description.string' => 'La descripción debe ser una cadena de caracteres'
        ];
    }
}
