<?php

namespace App\ModelS3;

use Illuminate\Database\Eloquent\Model;

class IdentificacionS3 extends Model
{
    protected $connection = 'SIEG3'; 
     
    protected $table = 'identificacionxpersonas';
}
