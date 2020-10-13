<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HojaIdioma extends Model
{
    protected $table = 'hojadevida_idiomas';
    public $timestamps = false;
    
    protected $fillable = ['idHoja', 'idIdioma', 'lectura', 'escritura', 'habla'];
    
    public function getidioma(){
        return  $this->belongsTo('App\Models\Idioma','idIdioma');
    }
    
    public function getnivellectura(){
        return  $this->belongsTo('App\Models\NivelIdioma','lectura');
    }
    
    public function getnivelescritura(){
        return  $this->belongsTo('App\Models\NivelIdioma','escritura');
    }
    public function getnivelhabla(){
        return  $this->belongsTo('App\Models\NivelIdioma','habla');
    }
    
}
