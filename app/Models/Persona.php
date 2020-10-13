<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public $timestamps = true;

    protected $table = 'personas';

    public $casts = [
        'recibir_mails' => 'boolean'
    ];
    
    public function getestudiantes()
    {
        return  $this->hasMany('App\Models\Estudiante','idPersona');
    }
    
    public function getgenero()
    {
        return  $this->belongsTo('App\Models\Genero','idGenero');
    }
    
    public function getestadocivil()
    {
        return  $this->belongsTo('App\Models\EstadoCivil','idEstadoCivil');
    }
    
    public function getciudad()
    {
        return  $this->belongsTo('App\Models\Municipio','ciudadOrigen');
    }

    public function getciudadres()
    {
        return  $this->belongsTo('App\Models\Municipio','ciudadResidencia');
    }
    
    public function getusuario()
    {
        return $this->hasOne('App\Models\User','idPersona');
    }

    public function gethojadevida()
    {
        return  $this->hasMany('App\Models\Hojavida','idPersona');
    }

    public function getptipocorreo()
    {
        return  $this->hasMany('App\Models\PersonaTipocorreo','idPersona');
    }
}