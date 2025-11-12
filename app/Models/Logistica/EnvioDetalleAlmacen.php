<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class EnvioDetalleAlmacen extends Model
{
    protected $table = 'logistica.enviodetallealmacen';
    protected $primaryKey = 'envio_detalle_alm_id';
    public $timestamps = false;
    protected $fillable = [
        'envio_id',
        'lote_salida_id',
        'almacen_id',
        'cantidad_t',
    ];
}

<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class EnvioDetalleAlmacen extends Model
{
    protected $table = 'logistica.enviodetallealmacen';
    protected $primaryKey = 'envio_detalle_alm_id';
    public $timestamps = false;
    protected $fillable = [
        'envio_id',
        'lote_salida_id',
        'almacen_id',
        'cantidad_t',
    ];
}


