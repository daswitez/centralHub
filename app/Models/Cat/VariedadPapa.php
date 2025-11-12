<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo VariedadPapa para tabla cat.variedadpapa
 * - PK: variedad_id (identity)
 * - Sin timestamps
 */
class VariedadPapa extends Model
{
    protected $table = 'cat.variedadpapa';
    protected $primaryKey = 'variedad_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_variedad',
        'nombre_comercial',
        'aptitud',
        'ciclo_dias_min',
        'ciclo_dias_max',
    ];
}


