<?php

namespace App\Http\Requests\Planta;

use Illuminate\Foundation\Http\FormRequest;

class ControlprocesoRequest extends FormRequest
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
			'control_id' => 'required',
			'lote_planta_id' => 'required',
			'etapa' => 'required|string',
			'fecha_hora' => 'required',
			'parametro' => 'required|string',
			'valor_texto' => 'string',
			'estado' => 'required|string',
        ];
    }
}
