<?php

namespace App\Providers;

use Artisaninweb\SoapWrapper\Extension\SoapService;

class WebServiceSil extends SoapService
{
    protected $name = 'WebServiceSil';    
    protected $wsdl = 'http://ayre.unimagdalena.edu.co/WebServicesAyRE/AllWebServicesAyreService?wsdl';
                      
                      
    public function functions()
    {
        return $this->getFunctions();
    }
    
    public function token($codigo)
    {
        $fecha = explode('-', \Carbon\Carbon::now()->toDateString())[2].'/'.explode('-', \Carbon\Carbon::now()->toDateString())[1].'/'.explode('-', \Carbon\Carbon::now()->toDateString())[0];
        // $fecha = '24-05-2016';//\Carbon\Carbon::now()->format('d/m/y');
        // dd($fecha);
        return str_replace('codigo', $codigo, str_replace('fecha', $fecha,env('TOKEN_ADMISIONES_SIL')));
        
    }


}