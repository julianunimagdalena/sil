<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticaTutor extends Model
{
    protected $table = 'practicas_tutores';
    
    public $timestamps = false;
    
    protected $fillable = ['idPracticas', 'idTutor'];
    
    public function getpractica()
    {
        return $this->belongsTo('App\Models\ModalidadEstudiante', 'idPracticas');
    }
    
    public function gettutor(){

        return  $this->belongsTo('App\Models\User','idTutor');

    }
}
