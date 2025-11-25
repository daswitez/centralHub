<?php

namespace App\Models\Campo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Campo.lotecampo
 *
 * @property $lote_campo_id
 * @property $codigo_lote_campo
 * @property $productor_id
 * @property $variedad_id
 * @property $superficie_ha
 * @property $fecha_siembra
 * @property $fecha_cosecha
 * @property $humedad_suelo_pct
 *
 * @property Productor $productor
 * @property Variedadpapa $variedadpapa
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Lotecampo extends Model
{
    protected $table = 'campo.lotecampo';
    protected $primaryKey = 'lote_campo_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['lote_campo_id', 'codigo_lote_campo', 'productor_id', 'variedad_id', 'superficie_ha', 'fecha_siembra', 'fecha_cosecha', 'humedad_suelo_pct'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productor()
    {
        return $this->belongsTo(\App\Models\Campo\Productor::class, 'productor_id', 'productor_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variedadpapa()
    {
        return $this->belongsTo(\App\Models\Variedadpapa::class, 'variedad_id', 'variedad_id');
    }
    
}
