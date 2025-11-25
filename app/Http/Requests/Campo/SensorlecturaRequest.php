<?php

namespace App\Http\Requests\Campo;

use Illuminate\Foundation\Http\FormRequest;

class SensorlecturaRequest extends FormRequest
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
			'lectura_id' => 'required',
			'lote_campo_id' => 'required',
			'fecha_hora' => 'required',
			'tipo' => 'required|string',
			'valor_texto' => 'string',
        ];
    }
}
