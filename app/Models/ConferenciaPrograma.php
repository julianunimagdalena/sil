<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenciaPrograma extends Model
{
    protected $table = 'conferenciaprograma';
    public $timestamps = false;
    
    protected $fillable = ['idConferencia', 'idPrograma'];
}
