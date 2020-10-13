<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $table ='estudiosrealizados';
    
    public $timestamps = false;
    
    protected $fillable = ['idHoja', 'institucion', 'titulo', 'idMunicipio', 'anioGrado', 'observaciones'];
    
    public function getmunicipio()
    {
        return  $this->belongsTo('App\Models\Municipio','idMunicipio');
    }
}
