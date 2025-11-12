<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'logistica.ruta';
    protected $primaryKey = 'ruta_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_ruta',
        'descripcion',
    ];
}

<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'logistica.ruta';
    protected $primaryKey = 'ruta_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_ruta',
        'descripcion',
    ];
}


