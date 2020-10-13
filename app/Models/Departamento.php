<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    
    public $timestamps = false;
    
    
    public function getpais(){

        return  $this->belongsTo('App\Models\Pais','idPais');

    }

    public function municipios () {
    	return $this->hasMany('App\Models\Municipio', 'idDepartamento')->orderBy('nombre');
    }
    
}