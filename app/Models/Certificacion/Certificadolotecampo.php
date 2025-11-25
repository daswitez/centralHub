<?php

namespace App\Models\Certificacion;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Certificacion.certificadolotecampo
 *
 * @property $certificado_id
 * @property $lote_campo_id
 *
 * @property Certificado $certificado
 * @property Lotecampo $lotecampo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Certificadolotecampo extends Model
{
    protected $table = 'certificacion.certificadolotecampo';
    protected $primaryKey = 'certificado_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['certificado_id', 'lote_campo_id'];


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
    public function lotecampo()
    {
        return $this->belongsTo(\App\Models\Lotecampo::class, 'lote_campo_id', 'lote_campo_id');
    }
    
}
