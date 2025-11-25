<?php

namespace App\Models\Planta;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Planta.loteplantum
 *
 * @property $lote_planta_id
 * @property $codigo_lote_planta
 * @property $planta_id
 * @property $fecha_inicio
 * @property $fecha_fin
 * @property $rendimiento_pct
 *
 * @property Plantum $plantum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Loteplanta extends Model
{
    protected $table = 'planta.loteplanta';
    protected $primaryKey = 'lote_planta_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['lote_planta_id', 'codigo_lote_planta', 'planta_id', 'fecha_inicio', 'fecha_fin', 'rendimiento_pct'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plantum()
    {
        return $this->belongsTo(\App\Models\Plantum::class, 'planta_id', 'planta_id');
    }
    
}
