<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfertaPrograma extends Model
{
    protected $table ='ofertas_programas';
    
    public $timestamps = false;
    
    protected $fillable = ['idOferta', 'idDependencia'];

    public function getprograma()
    {
    	return $this->belongsTo('App\Models\Dependencia', 'idDependencia');
    }

    public function getoferta()
    {
    	return $this->belongsTo('App\Models\Oferta', 'idOferta');
    }
}
