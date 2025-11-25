<?php

namespace App\Http\Requests\Cat;

use Illuminate\Foundation\Http\FormRequest;

class MunicipioRequest extends FormRequest
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
			'municipio_id' => 'required',
			'departamento_id' => 'required',
			'nombre' => 'required|string',
        ];
    }
}
