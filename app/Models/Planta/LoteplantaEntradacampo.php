<?php

namespace App\Models\Planta;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Planta.loteplantaEntradacampo
 *
 * @property $lote_planta_id
 * @property $lote_campo_id
 * @property $peso_entrada_t
 *
 * @property Lotecampo $lotecampo
 * @property Loteplantum $loteplantum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class LoteplantaEntradacampo extends Model
{
    protected $table = 'planta.loteplanta_entradacampo';
    protected $primaryKey = 'lote_planta_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['lote_planta_id', 'lote_campo_id', 'peso_entrada_t'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lotecampo()
    {
        return $this->belongsTo(\App\Models\Lotecampo::class, 'lote_campo_id', 'lote_campo_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loteplantum()
    {
        return $this->belongsTo(\App\Models\Loteplantum::class, 'lote_planta_id', 'lote_planta_id');
    }
    
}
