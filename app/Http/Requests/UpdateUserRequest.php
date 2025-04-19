<?php

namespace App\Http\Requests;

use App\Exceptions\UserNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    protected ?User $userToUpdate = null;

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $id = $this->route('user');

        $this->userToUpdate = User::find($id);

        if (!$this->userToUpdate) {
            throw new UserNotFoundException('Usuario no encontrado');
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userToUpdate->id),
            ],
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|in:user,admin'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de caracteres',
            'name.max' => 'Máximo 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'email.unique' => 'El correo electrónico ya está utilizado',
            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'La contraseña debe ser una cadena de caracteres',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres',
            'role.in' => 'El rol debe ser "user" o "admin"',
        ];
    }
}
