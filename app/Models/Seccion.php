<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'secciones';
    
    public $timestamps = false;
    
    protected $fillable = ['idEvaluacion', 'idPadre', 'enunciado'];
    
    public function getsecciones()
    {
        return  $this->hasMany('App\Models\Seccion','idPadre');
    }
    
    public function getpreguntas()
    {
        return  $this->hasMany('App\Models\Pregunta','idSeccion');
    }
    
    public function getpadre()
    {
        return  $this->belongsTo('App\Models\Seccion','idPadre');
    }
    
    public function getevaluacion()
    {
        return  $this->belongsTo('App\Models\Evaluacion','idEvaluacion');
    }
}
