<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutaPunto extends Model
{
    protected $table = 'logistica.rutapunto';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // composite key handled manually

    protected $fillable = [
        'ruta_id',
        'orden',
        'cliente_id',
    ];

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class, 'ruta_id', 'ruta_id');
    }
}

<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class RutaPunto extends Model
{
    protected $table = 'logistica.rutapunto';
    public $timestamps = false;
    protected $fillable = [
        'ruta_id',
        'orden',
        'cliente_id',
    ];
}


