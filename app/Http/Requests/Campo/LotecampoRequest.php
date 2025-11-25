<?php

namespace App\Http\Requests\Campo;

use Illuminate\Foundation\Http\FormRequest;

class LotecampoRequest extends FormRequest
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
			'lote_campo_id' => 'required',
			'codigo_lote_campo' => 'required|string',
			'productor_id' => 'required',
			'variedad_id' => 'required',
			'superficie_ha' => 'required',
			'fecha_siembra' => 'required',
        ];
    }
}
