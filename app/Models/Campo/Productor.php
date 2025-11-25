<?php

namespace App\Models\Campo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Campo.productor
 *
 * @property $productor_id
 * @property $codigo_productor
 * @property $nombre
 * @property $municipio_id
 * @property $telefono
 *
 * @property Municipio $municipio
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Productor extends Model
{
    protected $table = 'campo.productor';
    protected $primaryKey = 'productor_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['productor_id', 'codigo_productor', 'nombre', 'municipio_id', 'telefono'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Cat\Municipio::class, 'municipio_id', 'municipio_id');
    }
    
}
