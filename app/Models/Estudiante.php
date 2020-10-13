<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    
    public $timestamps = true;
    
    public function getpersona(){
        return  $this->belongsTo('App\Models\Persona','idPersona');
    }
    
    public function gettipo(){
        return  $this->belongsTo('App\Models\TipoEstudiante','idTipo');
    }
    
    public function getprograma(){
        return  $this->belongsTo('App\Models\DependenciaModalidad','idPrograma');
    }
    
    public function getmodalidades()
    {
        return $this->belongsToMany('App\Models\Modalidad', 'estudiantes_modalidades', 'idEstudiante', 'idModalidad');
    }
    
    public function getpracticas()
    {
        return $this->hasMany('App\Models\ModalidadEstudiante', 'idEstudiante');
    }
    
    public function getpostulaciones()
    {
        return $this->hasMany('App\Models\Postulado', 'idEstudiante');
    }
    
    public function getasistencias()
    {
        return $this->hasMany('App\Models\Asistencia', 'idEstudiante');
    }
    
    public function getcartas()
    {
        return $this->hasMany('App\Models\Carta', 'idEstudiante');
    }
}