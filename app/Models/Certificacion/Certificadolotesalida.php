<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificadolotesalida
 *
 * @property $certificado_id
 * @property $lote_salida_id
 *
 * @property Certificado $certificado
 * @property Lotesalida $lotesalida
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificadolotesalida extends Model
{
    protected $table = 'certificacion.certificadolotesalida';
    protected $primaryKey = 'certificado_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['certificado_id', 'lote_salida_id'];


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
    public function lotesalida()
    {
        return $this->belongsTo(\App\Models\Lotesalida::class, 'lote_salida_id', 'lote_salida_id');
    }
    
}
