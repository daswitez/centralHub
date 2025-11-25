<?php

namespace App\Http\Requests\Logistica;

use Illuminate\Foundation\Http\FormRequest;

class EnviodetallealmacenRequest extends FormRequest
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
			'envio_detalle_alm_id' => 'required',
			'envio_id' => 'required',
			'lote_salida_id' => 'required',
			'almacen_id' => 'required',
			'cantidad_t' => 'required',
        ];
    }
}
