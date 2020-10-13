<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadEstudiante extends Model
{
    protected $table= 'estudiantes_modalidades';
    
    public $timestamps = false;
    
    protected $fillable = ['idEstudiante', 'idModalidad'];
    
    public function getestudiante()
    {
        return  $this->belongsTo('App\Models\Estudiante','idEstudiante');
    }
    
    public function getmodalidad()
    {
        return  $this->belongsTo('App\Models\Modalidad','idModalidad');
    }
    
    public function gettutores()
    {
        return $this->belongsToMany('App\Models\User', 'practicas_tutores', 'idPracticas', 'idTutor');
    }
    
    public function getestado()
    {
        return  $this->belongsTo('App\Models\EstadoPractica','estado');
    }
    
    public function getciudad()
    {
        return  $this->belongsTo('App\Models\Municipio','idCiudad');
    }
    
    public function getvisitas()
    {
        return  $this->hasMany('App\Models\Visita','idPractica');
    }
    
    public function getpracticastutor()
    {
        return  $this->hasMany('App\Models\PracticaTutor','idPracticas');
    }
}
