<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'admin' => \App\Http\Middleware\Admin::class,
        'admineg' => \App\Http\Middleware\Sil::class,
        'adminsil' => \App\Http\Middleware\AdminSil::class,
        'empresa' => \App\Http\Middleware\Empresa::class,
        'crearoferta' => \App\Http\Middleware\CrearOferta::class,
        'activaroferta' => \App\Http\Middleware\ActivarOferta::class,
        'registro' => \App\Http\Middleware\Registro::class,
        'estudiante' => \App\Http\Middleware\Estudiante::class,
        'rechazar' => \App\Http\Middleware\RechazarPracticas::class,
        'practicas' => \App\Http\Middleware\Practicas::class,
        'prepracticas' => \App\Http\Middleware\Prepracticas::class,
        'ofertaEstudiante' => \App\Http\Middleware\OfertaEstudiante::class,
        'hoja' => \App\Http\Middleware\Hojadevida::class,
        'vinculacion' => \App\Http\Middleware\Vinculacion::class,
        'empresa_postulados' => \App\Http\Middleware\EmpresaPostulados::class,
        'jefe' => \App\Http\Middleware\Jefe::class,
        'tutor' => \App\Http\Middleware\Tutor::class,
        'val_eval' => \App\Http\Middleware\ValidarEvaluacion::class,
        'juridica' => \App\Http\Middleware\Juridica::class,
        'programa' => \App\Http\Middleware\Programa::class,
        'ori' => \App\Http\Middleware\Ori::class,
        'cdn' => \App\Http\Middleware\Coordinador::class,
        'admincdn' => \App\Http\Middleware\AdminCoordinador::class,
        'adminsilcdn' => \App\Http\Middleware\AdminSilCdn::class,
        'cambiocontrasena' => \App\Http\Middleware\Cambiarclave::class,
        'Hojaprivada' => \App\Http\Middleware\HojaPrivada::class,
        'empresadippro' => \App\Http\Middleware\EmpresaDippro::class,
    ];
}
