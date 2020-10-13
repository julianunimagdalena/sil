<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadEvaluacion extends Model
{
    protected $table = 'modalidad_evaluacion';
    
    public $timestamps = false;
    
    protected $fillable = ['idModalidad', 'idEvaluacion'];
}
