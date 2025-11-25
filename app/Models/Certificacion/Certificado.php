<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificado
 *
 * @property $certificado_id
 * @property $codigo_certificado
 * @property $ambito
 * @property $area
 * @property $vigente_desde
 * @property $vigente_hasta
 * @property $emisor
 * @property $url_archivo
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificado extends Model
{
    protected $table = 'certificacion.certificado';
    protected $primaryKey = 'certificado_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['certificado_id', 'codigo_certificado', 'ambito', 'area', 'vigente_desde', 'vigente_hasta', 'emisor', 'url_archivo'];


}
