<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminSil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     
    private $auth; 
    
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    } 
    
    public function handle($request, Closure $next)
    {
        if (session('rol')->nombre != 'Administrador Dippro' && session('rol')->nombre != 'Administrador Egresados') {
            
            $this->auth->logout();
            
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->to('/');
            }
        }
        
        return $next($request);
    }
}