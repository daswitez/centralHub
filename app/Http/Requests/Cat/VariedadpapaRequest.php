<?php

namespace App\Http\Requests\Cat;

use Illuminate\Foundation\Http\FormRequest;

class VariedadpapaRequest extends FormRequest
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
			'variedad_id' => 'required',
			'codigo_variedad' => 'required|string',
			'nombre_comercial' => 'required|string',
			'aptitud' => 'string',
        ];
    }
}
