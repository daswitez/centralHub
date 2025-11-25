<?php

namespace App\Http\Requests\Cat;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
			'cliente_id' => 'required',
			'codigo_cliente' => 'required|string',
			'nombre' => 'required|string',
			'tipo' => 'required|string',
			'direccion' => 'string',
        ];
    }
}
