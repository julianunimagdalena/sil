<?php

namespace App\Providers;

use Artisaninweb\SoapWrapper\Extension\SoapService;

class WebServiceSieg extends SoapService
{
    protected $name = 'WebServiceSieg';    
    protected $wsdl = 'http://ayre.unimagdalena.edu.co/WebServicesEgresados/WebServicesForEgresadosService?wsdl';
    public function functions()
    {
        return $this->getFunctions();
    }
    
    public function token($codigo)
    {
        $fecha = explode('-', \Carbon\Carbon::now()->toDateString())[2].'/'.explode('-', \Carbon\Carbon::now()->toDateString())[1].'/'.explode('-', \Carbon\Carbon::now()->toDateString())[0];//dd/mm/aaaa
        // $fecha = '24-05-2016';//\Carbon\Carbon::now()->format('d/m/y');
        // dd($fecha);
        return str_replace('codigo', $codigo, str_replace('fecha', $fecha,env('TOKEN_ADMISIONES_SIEG')));
        
    }


}