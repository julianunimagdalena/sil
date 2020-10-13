<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';
    
    public $timestamps = false;

    public function departamentos () {
    	return $this->hasMany('App\Models\Departamento', 'idPais')->orderBy('nombre');
    }
    
}