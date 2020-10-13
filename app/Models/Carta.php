<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    protected $table = 'cartas_prensentacion';
    public $timestamps = false;
    
    public function getestado(){
        return  $this->belongsTo('App\Models\EstadoCarta','estado');
    }
    
    public function getestudiante(){
        return  $this->belongsTo('App\Models\Estudiante','idEstudiante');
    }
    
    public function getempresa(){
        return  $this->belongsTo('App\Models\Empresa','idEmpresa');
    }
}
