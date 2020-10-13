<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoordinadorDependencia extends Model
{
    protected $table = 'coordinador_programas';
    
    public $timestamps = false;
    
    protected $fillable = ['idCoordinador', 'idPrograma'];
}
