<?php

namespace App\Models\Comercial;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comercial.pedidodetalle
 *
 * @property $pedido_detalle_id
 * @property $pedido_id
 * @property $sku
 * @property $cantidad_t
 * @property $precio_unit_usd
 *
 * @property Pedido $pedido
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pedidodetalle extends Model
{
    protected $table = 'comercial.pedidodetalle';
    protected $primaryKey = 'pedido_detalle_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['pedido_detalle_id', 'pedido_id', 'sku', 'cantidad_t', 'precio_unit_usd'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(\App\Models\Pedido::class, 'pedido_id', 'pedido_id');
    }
    
}
