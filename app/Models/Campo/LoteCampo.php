<?php

namespace App\Models\Campo;

use App\Models\Cat\VariedadPapa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoteCampo extends Model
{
    protected $table = 'campo.lotecampo';
    protected $primaryKey = 'lote_campo_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_lote_campo',
        'productor_id',
        'variedad_id',
        'superficie_ha',
        'fecha_siembra',
        'fecha_cosecha',
        'humedad_suelo_pct',
    ];

    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class, 'productor_id', 'productor_id');
    }

    public function variedad(): BelongsTo
    {
        return $this->belongsTo(VariedadPapa::class, 'variedad_id', 'variedad_id');
    }

    /** Lecturas de sensores asociadas al lote */
    public function lecturas(): HasMany
    {
        return $this->hasMany(SensorLectura::class, 'lote_campo_id', 'lote_campo_id');
    }
}


