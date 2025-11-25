<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logistica.rutum
 *
 * @property $ruta_id
 * @property $codigo_ruta
 * @property $descripcion
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ruta extends Model
{
    protected $table = 'logistica.ruta';
    protected $primaryKey = 'ruta_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['ruta_id', 'codigo_ruta', 'descripcion'];


}
