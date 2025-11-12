<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'logistica.envio';
    protected $primaryKey = 'envio_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_envio',
        'ruta_id',
        'transportista_id',
        'fecha_salida',
        'fecha_llegada',
        'temp_min_c',
        'temp_max_c',
        'estado',
        'almacen_origen_id',
    ];
}

<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'logistica.envio';
    protected $primaryKey = 'envio_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_envio',
        'ruta_id',
        'transportista_id',
        'fecha_salida',
        'fecha_llegada',
        'temp_min_c',
        'temp_max_c',
        'estado',
        'almacen_origen_id',
    ];
}


