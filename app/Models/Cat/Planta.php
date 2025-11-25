<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.plantum
 *
 * @property $planta_id
 * @property $codigo_planta
 * @property $nombre
 * @property $municipio_id
 * @property $direccion
 * @property $lat
 * @property $lon
 *
 * @property Municipio $municipio
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Planta extends Model
{
    protected $table = 'cat.planta';
    protected $primaryKey = 'planta_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['planta_id', 'codigo_planta', 'nombre', 'municipio_id', 'direccion', 'lat', 'lon'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Cat\Municipio::class, 'municipio_id', 'municipio_id');
    }
    
}
