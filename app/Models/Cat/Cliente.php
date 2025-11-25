<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.cliente
 *
 * @property $cliente_id
 * @property $codigo_cliente
 * @property $nombre
 * @property $tipo
 * @property $municipio_id
 * @property $direccion
 * @property $lat
 * @property $lon
 *
 * @property Municipio $municipio
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cliente extends Model
{
    protected $table = 'cat.cliente';
    protected $primaryKey = 'cliente_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cliente_id', 'codigo_cliente', 'nombre', 'tipo', 'municipio_id', 'direccion', 'lat', 'lon'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Cat\Municipio::class, 'municipio_id', 'municipio_id');
    }
    
}
