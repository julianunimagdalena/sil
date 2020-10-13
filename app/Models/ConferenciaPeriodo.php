<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenciaPeriodo extends Model
{
    protected $table = 'conferencias_periodos';
    
    public $timestamps = false;
    
    
    public function getorador()
    {
        return $this->belongsTo('App\Models\Persona', 'orador');
    }
    
    public function getconferencia()
    {
        return $this->belongsTo('App\Models\Conferencia', 'idConferencia');
    }
    
    public function getperiodo()
    {
        return $this->belongsTo('App\Models\Periodo', 'idPeriodo');
    }
    
    public function getasistencias()
    {
        return $this->hasMany('App\Models\Asistencia', 'idConferenciaPeriodo');
    }
}
