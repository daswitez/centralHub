<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    protected $table = 'cat.transportista';
    protected $primaryKey = 'transportista_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_transp',
        'nombre',
        'nro_licencia',
    ];
}


