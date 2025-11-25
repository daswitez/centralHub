<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Almacen.recepcion
 *
 * @property $recepcion_id
 * @property $envio_id
 * @property $almacen_id
 * @property $fecha_recepcion
 * @property $observacion
 *
 * @property Almacen $almacen
 * @property Envio $envio
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Recepcion extends Model
{
    protected $table = 'almacen.recepcion';
    protected $primaryKey = 'recepcion_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['recepcion_id', 'envio_id', 'almacen_id', 'fecha_recepcion', 'observacion'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(\App\Models\Cat\Almacen::class, 'almacen_id', 'almacen_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function envio()
    {
        return $this->belongsTo(\App\Models\Logistica\Envio::class, 'envio_id', 'envio_id');
    }
    
}
