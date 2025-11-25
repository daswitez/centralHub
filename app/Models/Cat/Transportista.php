<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.transportistum
 *
 * @property $transportista_id
 * @property $codigo_transp
 * @property $nombre
 * @property $nro_licencia
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Transportista extends Model
{
    protected $table = 'cat.transportista';
    protected $primaryKey = 'transportista_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['transportista_id', 'codigo_transp', 'nombre', 'nro_licencia'];


}
