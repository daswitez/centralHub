<?php

namespace App\Models\Campo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Campo.sensorlectura
 *
 * @property $lectura_id
 * @property $lote_campo_id
 * @property $fecha_hora
 * @property $tipo
 * @property $valor_num
 * @property $valor_texto
 *
 * @property Lotecampo $lotecampo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Sensorlectura extends Model
{
    protected $table = 'campo.sensorlectura';
    protected $primaryKey = 'lectura_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['lectura_id', 'lote_campo_id', 'fecha_hora', 'tipo', 'valor_num', 'valor_texto'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lotecampo()
    {
        return $this->belongsTo(\App\Models\Lotecampo::class, 'lote_campo_id', 'lote_campo_id');
    }
    
}
