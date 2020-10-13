<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table='asistencias';
    
    public $timestamps = false;
    
    protected $fillable = ['idConferenciaPeriodo', 'idEstudiante'];
    
    public function gethorario()
    {
        return $this->belongsTo('App\Models\ConferenciaPeriodo','idConferenciaPeriodo');
    }
    
    public function getestudiante()
    {
        return $this->belongsTo('App\Models\Estudiante','idEstudiante');
    }
}
