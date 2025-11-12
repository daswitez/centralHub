<?php

namespace App\Models\Campo;

use App\Models\Cat\Municipio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Productor extends Model
{
    protected $table = 'campo.productor';
    protected $primaryKey = 'productor_id';
    public $timestamps = false;
    protected $fillable = [
        'codigo_productor',
        'nombre',
        'municipio_id',
        'telefono',
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'municipio_id');
    }
}


