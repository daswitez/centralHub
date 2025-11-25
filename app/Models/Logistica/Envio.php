<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logistica.envio
 *
 * @property $envio_id
 * @property $codigo_envio
 * @property $ruta_id
 * @property $transportista_id
 * @property $fecha_salida
 * @property $fecha_llegada
 * @property $temp_min_c
 * @property $temp_max_c
 * @property $estado
 * @property $almacen_origen_id
 *
 * @property Almacen $almacen
 * @property Rutum $rutum
 * @property Transportistum $transportistum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Envio extends Model
{
    protected $table = 'logistica.envio';
    protected $primaryKey = 'envio_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['envio_id', 'codigo_envio', 'ruta_id', 'transportista_id', 'fecha_salida', 'fecha_llegada', 'temp_min_c', 'temp_max_c', 'estado', 'almacen_origen_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(\App\Models\Cat\Almacen::class, 'almacen_origen_id', 'almacen_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rutum()
    {
        return $this->belongsTo(\App\Models\Rutum::class, 'ruta_id', 'ruta_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transportistum()
    {
        return $this->belongsTo(\App\Models\Transportistum::class, 'transportista_id', 'transportista_id');
    }
    
}
