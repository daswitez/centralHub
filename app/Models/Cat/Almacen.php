<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.almacen
 *
 * @property $almacen_id
 * @property $codigo_almacen
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
class Almacen extends Model
{
    protected $table = 'cat.almacen';
    protected $primaryKey = 'almacen_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['almacen_id', 'codigo_almacen', 'nombre', 'municipio_id', 'direccion', 'lat', 'lon'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Cat\Municipio::class, 'municipio_id', 'municipio_id');
    }
    
}
