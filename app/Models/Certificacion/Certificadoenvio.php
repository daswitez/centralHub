<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificadoenvio
 *
 * @property $certificado_id
 * @property $envio_id
 *
 * @property Certificado $certificado
 * @property Envio $envio
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificadoenvio extends Model
{
    protected $table = 'certificacion.certificadoenvio';
    protected $primaryKey = 'certificado_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['certificado_id', 'envio_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificado()
    {
        return $this->belongsTo(\App\Models\Certificacion\Certificado::class, 'certificado_id', 'certificado_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function envio()
    {
        return $this->belongsTo(\App\Models\Logistica\Envio::class, 'envio_id', 'envio_id');
    }
    
}
