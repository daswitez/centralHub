<?php

namespace App\Models\Comercial;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comercial.pedido
 *
 * @property $pedido_id
 * @property $codigo_pedido
 * @property $cliente_id
 * @property $fecha_pedido
 * @property $estado
 * @property $almacen_id
 *
 * @property Almacen $almacen
 * @property Cliente $cliente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pedido extends Model
{
    protected $table = 'comercial.pedido';
    protected $primaryKey = 'pedido_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['pedido_id', 'codigo_pedido', 'cliente_id', 'fecha_pedido', 'estado', 'almacen_id'];


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
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cat\Cliente::class, 'cliente_id', 'cliente_id');
    }
    
}
