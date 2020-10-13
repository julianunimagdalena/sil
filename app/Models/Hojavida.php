<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hojavida extends Model
{
    protected $table = 'hojavida';
    
    public $timestamps = false;
    
    public function getestudiante(){
        return  $this->belongsTo('App\Models\Estudiante','idEstudiate');
    }
    
    public function getestudios()
    {
        return  $this->hasMany('App\Models\Estudio','idHoja');
    }
    
    public function getnivelidiomas()
    {
        return  $this->hasMany('App\Models\HojaIdioma','idHoja');
    }
    
    public function getidiomas()
    {
        return $this->belongsToMany('App\Models\Idioma', 'hojadevida_idiomas', 'idHoja', 'idIdioma');
    }

    public function getidiomashv()
    {
        return $this->hasMany('App\Models\HojaIdioma', 'idHoja');
    }
    
    public function getcompetencias()
    {
        return $this->belongsToMany('App\Models\Competencia', 'competencias_hojadevida', 'idHoja', 'idCompetencia');
    }
    
    public function getexperiencias()
    {
        return  $this->hasMany('App\Models\Experiencia','idHoja');
    }
    
    public function getreferencias()
    {
        return  $this->hasMany('App\Models\Referencia','idHoja');
    }
    
    public function getdiscapacidades () {
        return $this->belongsToMany('App\Models\Discapacidad', 'hoja_discapacidades', 'idHoja', 'idDiscapacidad');
    }

    public function getdistinciones () {
        return $this->hasMany('App\Models\Distinciones', 'idHoja');
    }
}
