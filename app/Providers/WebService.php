<?php

namespace App\Providers;

use Artisaninweb\SoapWrapper\Extension\SoapService;

class WebService extends SoapService
{
    protected $name = 'WebService';    
    // protected $wsdl = 'http://ayre.unimagdalena.edu.co/WebServicesDippro/AllWebServicesDipproService?wsdl';
    protected $wsdl = 'http://ayre.unimagdalena.edu.co/WebServicesDippro/AllWebServicesDipproService?wsdl';
    public function functions()
    {
        return $this->getFunctions();
    }


}