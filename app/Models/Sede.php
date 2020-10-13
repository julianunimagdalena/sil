<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedes';
    
    public $timestamps = false;
    
    public function getempresa () {

        return  $this->belongsTo('App\Models\Empresa','idEmpresa');
    }

    public function getofertas () {
        return $this->hasMany('App\Models\Oferta','idSede');
    }
    
    public function getmunicipio () {
        return  $this->belongsTo('App\Models\Municipio','idMunicipio');
    }
    
    public function getusuarios () {
        return  $this->hasMany('App\Models\User','idSede');
    }
}