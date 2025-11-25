<?php

namespace App\Http\Requests\Campo;

use Illuminate\Foundation\Http\FormRequest;

class ProductorRequest extends FormRequest
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
			'productor_id' => 'required',
			'codigo_productor' => 'required|string',
			'nombre' => 'required|string',
			'municipio_id' => 'required',
			'telefono' => 'string',
        ];
    }
}
