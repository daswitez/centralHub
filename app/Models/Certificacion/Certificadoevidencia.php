<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificadoevidencium
 *
 * @property $evidencia_id
 * @property $certificado_id
 * @property $tipo
 * @property $descripcion
 * @property $url_archivo
 * @property $fecha_registro
 *
 * @property Certificado $certificado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificadoevidencia extends Model
{
    protected $table = 'certificacion.certificadoevidencia';
    protected $primaryKey = 'evidencia_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['evidencia_id', 'certificado_id', 'tipo', 'descripcion', 'url_archivo', 'fecha_registro'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificado()
    {
        return $this->belongsTo(\App\Models\Certificacion\Certificado::class, 'certificado_id', 'certificado_id');
    }
    
}
