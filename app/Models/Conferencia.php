<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conferencia extends Model
{
    protected $table = 'conferencia';
    public $timestamps = false;
    
    public function gethorarios()
    {
        return $this->hasMany('App\Models\ConferenciaPeriodo', 'idConferencia');
    }
    
    public function getprogramas()
    {
        return $this->belongsToMany('App\Models\Dependencia', 'conferenciaprograma', 'idConferencia', 'idPrograma');
    }
}
