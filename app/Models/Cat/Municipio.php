<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.municipio
 *
 * @property $municipio_id
 * @property $departamento_id
 * @property $nombre
 *
 * @property Departamento $departamento
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Municipio extends Model
{
    protected $table = 'cat.municipio';
    protected $primaryKey = 'municipio_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['municipio_id', 'departamento_id', 'nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento()
    {
        return $this->belongsTo(\App\Models\Cat\Departamento::class, 'departamento_id', 'departamento_id');
    }
    
}
