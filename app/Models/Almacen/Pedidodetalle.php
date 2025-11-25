<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Almacen.pedidodetalle
 *
 * @property $pedido_detalle_id
 * @property $pedido_almacen_id
 * @property $sku
 * @property $cantidad_t
 * @property $lote_salida_id
 *
 * @property Lotesalida $lotesalida
 * @property Pedido $pedido
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pedidodetalle extends Model
{
    protected $table = 'almacen.pedidodetalle';
    protected $primaryKey = 'pedido_detalle_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['pedido_detalle_id', 'pedido_almacen_id', 'sku', 'cantidad_t', 'lote_salida_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lotesalida()
    {
        return $this->belongsTo(\App\Models\Lotesalida::class, 'lote_salida_id', 'lote_salida_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(\App\Models\Pedido::class, 'pedido_almacen_id', 'pedido_almacen_id');
    }
    
}
