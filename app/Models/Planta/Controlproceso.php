<?php

namespace App\Models\Planta;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Planta.controlproceso
 *
 * @property $control_id
 * @property $lote_planta_id
 * @property $etapa
 * @property $fecha_hora
 * @property $parametro
 * @property $valor_num
 * @property $valor_texto
 * @property $estado
 *
 * @property Loteplantum $loteplantum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Controlproceso extends Model
{
    protected $table = 'planta.controlproceso';
    protected $primaryKey = 'control_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['control_id', 'lote_planta_id', 'etapa', 'fecha_hora', 'parametro', 'valor_num', 'valor_texto', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loteplantum()
    {
        return $this->belongsTo(\App\Models\Loteplantum::class, 'lote_planta_id', 'lote_planta_id');
    }
    
}
