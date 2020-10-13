<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table ='experiencias_laborales';
    
    public $timestamps = false;
    
    protected $fillable = ['idHoja', 'empresa', 'cargo', 'duracion', 'funcioneslogros'];

    public function duracion () {
    	return $this->belongsTo('App\Models\Duracion', 'duracion');
    }
    
    public function municipio () {
    	return $this->belongsTo('App\Models\Municipio', 'municipio_id');
    }
}
