<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Departamento para tabla cat.departamento
 * - PK: departamento_id (identity)
 * - Sin timestamps
 */
class Departamento extends Model
{
    protected $table = 'cat.departamento';
    protected $primaryKey = 'departamento_id';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    /** Relación: un departamento tiene muchos municipios */
    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'departamento_id', 'departamento_id');
    }
}


