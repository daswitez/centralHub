<?php

namespace App\Http\Requests\Planta;

use Illuminate\Foundation\Http\FormRequest;

class LotesalidaRequest extends FormRequest
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
			'lote_salida_id' => 'required',
			'codigo_lote_salida' => 'required|string',
			'lote_planta_id' => 'required',
			'sku' => 'required|string',
			'peso_t' => 'required',
			'fecha_empaque' => 'required',
        ];
    }
}
