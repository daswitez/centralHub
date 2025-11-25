<?php

namespace App\Http\Requests\Almacen;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoRequest extends FormRequest
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
			'mov_id' => 'required',
			'almacen_id' => 'required',
			'lote_salida_id' => 'required',
			'tipo' => 'required|string',
			'cantidad_t' => 'required',
			'fecha_mov' => 'required',
			'referencia' => 'string',
			'detalle' => 'string',
        ];
    }
}
