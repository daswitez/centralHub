<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $table = 'cat.cliente';
    protected $primaryKey = 'cliente_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_cliente',
        'nombre',
        'tipo',
        'municipio_id',
        'direccion',
        'lat',
        'lon',
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'municipio_id');
    }
}


