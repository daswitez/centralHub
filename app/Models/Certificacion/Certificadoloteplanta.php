<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificadoloteplantum
 *
 * @property $certificado_id
 * @property $lote_planta_id
 *
 * @property Certificado $certificado
 * @property Loteplantum $loteplantum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificadoloteplanta extends Model
{
    protected $table = 'certificacion.certificadoloteplanta';
    protected $primaryKey = 'certificado_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['certificado_id', 'lote_planta_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificado()
    {
        return $this->belongsTo(\App\Models\Certificacion\Certificado::class, 'certificado_id', 'certificado_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loteplantum()
    {
        return $this->belongsTo(\App\Models\Loteplantum::class, 'lote_planta_id', 'lote_planta_id');
    }
    
}
