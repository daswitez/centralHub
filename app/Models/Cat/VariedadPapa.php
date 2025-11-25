<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.variedadpapa
 *
 * @property $variedad_id
 * @property $codigo_variedad
 * @property $nombre_comercial
 * @property $aptitud
 * @property $ciclo_dias_min
 * @property $ciclo_dias_max
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Variedadpapa extends Model
{
    protected $table = 'cat.variedadpapa';
    protected $primaryKey = 'variedad_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['variedad_id', 'codigo_variedad', 'nombre_comercial', 'aptitud', 'ciclo_dias_min', 'ciclo_dias_max'];


}
