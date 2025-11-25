<?php

namespace App\Models\Planta;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Planta.lotesalida
 *
 * @property $lote_salida_id
 * @property $codigo_lote_salida
 * @property $lote_planta_id
 * @property $sku
 * @property $peso_t
 * @property $fecha_empaque
 *
 * @property Loteplantum $loteplantum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Lotesalida extends Model
{
    protected $table = 'planta.lotesalida';
    protected $primaryKey = 'lote_salida_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['lote_salida_id', 'codigo_lote_salida', 'lote_planta_id', 'sku', 'peso_t', 'fecha_empaque'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loteplantum()
    {
        return $this->belongsTo(\App\Models\Loteplantum::class, 'lote_planta_id', 'lote_planta_id');
    }
    
}
