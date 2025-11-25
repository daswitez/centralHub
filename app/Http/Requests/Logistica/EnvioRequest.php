<?php

namespace App\Http\Requests\Logistica;

use Illuminate\Foundation\Http\FormRequest;

class EnvioRequest extends FormRequest
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
			'envio_id' => 'required',
			'codigo_envio' => 'required|string',
			'fecha_salida' => 'required',
			'estado' => 'required|string',
        ];
    }
}
