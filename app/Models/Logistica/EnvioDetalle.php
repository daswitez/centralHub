<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class EnvioDetalle extends Model
{
    protected $table = 'logistica.enviodetalle';
    protected $primaryKey = 'envio_detalle_id';
    public $timestamps = false;
    protected $fillable = [
        'envio_id',
        'lote_salida_id',
        'cliente_id',
        'cantidad_t',
    ];
}

<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class EnvioDetalle extends Model
{
    protected $table = 'logistica.enviodetalle';
    protected $primaryKey = 'envio_detalle_id';
    public $timestamps = false;
    protected $fillable = [
        'envio_id',
        'lote_salida_id',
        'cliente_id',
        'cantidad_t',
    ];
}


