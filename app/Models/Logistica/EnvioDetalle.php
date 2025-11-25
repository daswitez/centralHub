<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logistica.enviodetalle
 *
 * @property $envio_detalle_id
 * @property $envio_id
 * @property $lote_salida_id
 * @property $cliente_id
 * @property $cantidad_t
 *
 * @property Cliente $cliente
 * @property Envio $envio
 * @property Lotesalida $lotesalida
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Enviodetalle extends Model
{
    protected $table = 'logistica.enviodetalle';
    protected $primaryKey = 'envio_detalle_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['envio_detalle_id', 'envio_id', 'lote_salida_id', 'cliente_id', 'cantidad_t'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cat\Cliente::class, 'cliente_id', 'cliente_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function envio()
    {
        return $this->belongsTo(\App\Models\Logistica\Envio::class, 'envio_id', 'envio_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lotesalida()
    {
        return $this->belongsTo(\App\Models\Lotesalida::class, 'lote_salida_id', 'lote_salida_id');
    }
    
}
