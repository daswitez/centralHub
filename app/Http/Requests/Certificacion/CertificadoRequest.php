<?php

namespace App\Http\Requests\Certificacion;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoRequest extends FormRequest
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
			'certificado_id' => 'required',
			'codigo_certificado' => 'required|string',
			'ambito' => 'required|string',
			'area' => 'required|string',
			'vigente_desde' => 'required',
			'emisor' => 'required|string',
			'url_archivo' => 'string',
        ];
    }
}
