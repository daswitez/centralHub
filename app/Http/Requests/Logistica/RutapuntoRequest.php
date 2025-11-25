<?php

namespace App\Http\Requests\Logistica;

use Illuminate\Foundation\Http\FormRequest;

class RutapuntoRequest extends FormRequest
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
			'ruta_id' => 'required',
			'orden' => 'required',
			'cliente_id' => 'required',
        ];
    }
}
