<?php

namespace App\Models\Campo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorLectura extends Model
{
    protected $table = 'campo.sensorlectura';
    protected $primaryKey = 'lectura_id';
    public $timestamps = false;
    protected $fillable = [
        'lote_campo_id',
        'fecha_hora',
        'tipo',
        'valor_num',
        'valor_texto',
    ];

    public function lote(): BelongsTo
    {
        return $this->belongsTo(LoteCampo::class, 'lote_campo_id', 'lote_campo_id');
    }
}


