<?php

namespace App\Models\Cat;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cat.departamento
 *
 * @property $departamento_id
 * @property $nombre
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Departamento extends Model
{
    protected $table = 'cat.departamento';
    protected $primaryKey = 'departamento_id';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['departamento_id', 'nombre'];


}
