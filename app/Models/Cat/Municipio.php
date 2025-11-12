<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Municipio para tabla cat.municipio
 * - PK: municipio_id (identity)
 * - FK: departamento_id -> cat.departamento
 * - Sin timestamps
 */
class Municipio extends Model
{
    protected $table = 'cat.municipio';
    protected $primaryKey = 'municipio_id';
    public $timestamps = false;
    protected $fillable = ['departamento_id', 'nombre'];

    /** Relación: municipio pertenece a un departamento */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }
}


