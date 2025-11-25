<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logistica.rutapunto
 *
 * @property $ruta_id
 * @property $orden
 * @property $cliente_id
 *
 * @property Cliente $cliente
 * @property Rutum $rutum
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rutapunto extends Model
{
    protected $table = 'logistica.rutapunto';
    protected $primaryKey = 'ruta_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['ruta_id', 'orden', 'cliente_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cat\Cliente::class, 'cliente_id', 'cliente_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rutum()
    {
        return $this->belongsTo(\App\Models\Rutum::class, 'ruta_id', 'ruta_id');
    }
    
}
