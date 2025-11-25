<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logistica.enviodetallealmacen
 *
 * @property $envio_detalle_alm_id
 * @property $envio_id
 * @property $lote_salida_id
 * @property $almacen_id
 * @property $cantidad_t
 *
 * @property Almacen $almacen
 * @property Envio $envio
 * @property Lotesalida $lotesalida
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Enviodetallealmacen extends Model
{
    protected $table = 'logistica.enviodetallealmacen';
    protected $primaryKey = 'envio_detalle_alm_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['envio_detalle_alm_id', 'envio_id', 'lote_salida_id', 'almacen_id', 'cantidad_t'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(\App\Models\Cat\Almacen::class, 'almacen_id', 'almacen_id');
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
