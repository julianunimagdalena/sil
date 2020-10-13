<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table='responde';
    
    public $timestamps = false;
    
    protected $fillable = ['idRespuesta', 'respuestaLibre', 'respuestaCuantitativa', 'respuestaBooleana', 'idUsuario', 'idEvaluado', 'idPregunta'];
    
    public function getpregunta()
    {
        return $this->belongsTo('App\Models\Pregunta','idPregunta');
    }
    
    public function getevaluado()
    {
        return $this->belongsTo('App\Models\User','idEvaluado');
    }
    
    public function getevaluador()
    {
        return $this->belongsTo('App\Models\User','idUsuario');
    }
}
