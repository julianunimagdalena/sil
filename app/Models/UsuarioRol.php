<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    public $timestamps = false;

    protected $table = 'usuario_rol';

    public $casts = [
    	'activo' => 'boolean'
    ];
}
