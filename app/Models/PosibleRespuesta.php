<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosibleRespuesta extends Model
{
    protected $table='posibles_respuestas';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre'];
    
}
