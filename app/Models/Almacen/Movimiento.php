<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Almacen.movimiento
 *
 * @property $mov_id
 * @property $almacen_id
 * @property $lote_salida_id
 * @property $tipo
 * @property $cantidad_t
 * @property $fecha_mov
 * @property $referencia
 * @property $detalle
 *
 * @property Almacen $almacen
 * @property Lotesalida $lotesalida
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Movimiento extends Model
{
    protected $table = 'almacen.movimiento';
    protected $primaryKey = 'mov_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['mov_id', 'almacen_id', 'lote_salida_id', 'tipo', 'cantidad_t', 'fecha_mov', 'referencia', 'detalle'];


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
    public function lotesalida()
    {
        return $this->belongsTo(\App\Models\Lotesalida::class, 'lote_salida_id', 'lote_salida_id');
    }
    
}
