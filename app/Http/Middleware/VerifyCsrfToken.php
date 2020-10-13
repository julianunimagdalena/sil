<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
         '/users/save',
         '/home/registro',
         '/home/login',
         '/estudiante/legalizar',
         '/estudiante/informepracticas',
         '/empresa/saveoferta',
         '/empresa/subirdocs',
         '/estudiante/otraslegalizar',
         '/estudiante/calificarconferencia',
         '/estudiante/solicitarcarta',
         'home/cambiarclave',
         'graduado/savedatospersonales',
    ];
}
