<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Almacen.inventario
 *
 * @property $almacen_id
 * @property $lote_salida_id
 * @property $sku
 * @property $cantidad_t
 *
 * @property Almacen $almacen
 * @property Lotesalida $lotesalida
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Inventario extends Model
{
    protected $table = 'almacen.inventario';
    protected $primaryKey = 'almacen_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['almacen_id', 'lote_salida_id', 'sku', 'cantidad_t'];


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
