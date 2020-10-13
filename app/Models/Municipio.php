<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';
    
    public $timestamps = false;
    
    
    public function getdepartamento(){

        return  $this->belongsTo('App\Models\Departamento','idDepartamento');

    }
    
}