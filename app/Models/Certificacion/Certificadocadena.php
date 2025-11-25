<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificadocadena
 *
 * @property $certificado_padre_id
 * @property $certificado_hijo_id
 *
 * @property Certificado $certificado
 * @property Certificado $certificado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificadocadena extends Model
{
    protected $table = 'certificacion.certificadocadena';
    protected $primaryKey = 'certificado_padre_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['certificado_padre_id', 'certificado_hijo_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificadoHijo()
    {
        return $this->belongsTo(\App\Models\Certificacion\Certificado::class, 'certificado_hijo_id', 'certificado_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificadoPadre()
    {
        return $this->belongsTo(\App\Models\Certificacion\Certificado::class, 'certificado_padre_id', 'certificado_id');
    }
    
}
