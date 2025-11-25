<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Almacen.pedido
 *
 * @property $pedido_almacen_id
 * @property $codigo_pedido
 * @property $almacen_id
 * @property $fecha_pedido
 * @property $estado
 *
 * @property Almacen $almacen
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pedido extends Model
{
    protected $table = 'almacen.pedido';
    protected $primaryKey = 'pedido_almacen_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['pedido_almacen_id', 'codigo_pedido', 'almacen_id', 'fecha_pedido', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(\App\Models\Cat\Almacen::class, 'almacen_id', 'almacen_id');
    }
    
}
