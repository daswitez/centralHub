<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planta extends Model
{
    protected $table = 'cat.planta';
    protected $primaryKey = 'planta_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_planta',
        'nombre',
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


