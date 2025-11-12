<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Almacen extends Model
{
    protected $table = 'cat.almacen';
    protected $primaryKey = 'almacen_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_almacen',
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


