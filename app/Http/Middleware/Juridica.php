<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Juridica
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
        if (Auth::user()->getrol->nombre != 'Jurídica') {
            
            Auth::logout();
            
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->to('/');
            }
        }
        return $next($request);
    }
}
