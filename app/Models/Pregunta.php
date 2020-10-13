<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['idSeccion', 'idTipoPregunta', 'enunciado', 'minimo', 'maximo'];
    
    public function gettipo()
    {
        return  $this->belongsTo('App\Models\TipoPregunta','idTipoPregunta');
    }
    
    public function getseccion()
    {
        return  $this->belongsTo('App\Models\Seccion','idSeccion');
    }
    
    public function getposiblesrespuestas()
    {
        return $this->belongsToMany('App\Models\PosibleRespuesta', 'pregunta_respuesta', 'idPregunta', 'idRespuesta');
    }
    
    public function getpivoterespuesta()
    {
        return $this->hasMany('App\Models\PreguntaRespuesta','idPregunta');
    }
    
    public function getrespuestas()
    {
        return $this->hasMany('App\Models\Repuesta','idPregunta');
    }
}
