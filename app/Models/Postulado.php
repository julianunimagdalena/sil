<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulado extends Model
{
    protected $table = 'postulados';
    
    public $timestamps = false;
    
    public function getpersona()
    {
        return $this->belongsTo('App\Models\Persona', 'idPersona');
    }
    
    public function getestadoempresa()
    {
        return $this->belongsTo('App\Models\EstadoPostulado', 'idEstatoEmpresa');
    }
    
    public function getestadoestudiante()
    {
        return $this->belongsTo('App\Models\EstadoPostuladoEst', 'idEstadoEstudiante');
    }
    
    public function getoferta()
    {
        return $this->belongsTo('App\Models\Oferta', 'idOferta');
    }
}
