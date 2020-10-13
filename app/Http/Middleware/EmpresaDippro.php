<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class EmpresaDippro
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->getsede->getempresa->getestadodipro->nombre != 'ACEPTADA')
        {
            return redirect()->to('/');
        }


        return $next($request);
    }
}