<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\Sede;

use Carbon\Carbon;
use Auth;

class OfertaRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        //$this->request->add(array('fecha_cierre' => $this->fecha_cierre));
        //$this->merge(array('fecha_cierre' => strtotime($this->fecha_cierre)));
        $this->merge(array('arl' => $this->arl === '1'? 1: 0 ));//$test_mode_mail = $string === 'true'? true: false;
        $this->merge(array('salud' => $this->salud === '1'? 1: 0)); // (bool) $this->arl
        // $this->fechacierre = 
        // dd($this->all());
        //dd($this->fecha_cierre);
        $now = Carbon::now();
        $idSede = null;
        if(Auth::user()->getrol->nombre == 'Administrador Dippro')
        {
            if(isset($this->empresa) || $this->empresa==null)
            {
                $idSede = $this->empresa['id'];
            }
        }
        else if(Auth::user()->getrol->nombre == 'Empresa')
        {
            $idSede = Auth::user()->idSede;
        }
        
        $sede = Sede::find($idSede);
        
        if($sede->getempresa->getestadodipro->nombre == 'ACEPTADA' && $sede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            if(!isset($this->tipo))
            {
                if(Auth::user()->getrol->nombre != 'Administrador Dippro' && Auth::user()->getrol->nombre != 'Administrador Egresados')
                {
                    return [
                        'tipo.id'=>'required',
                    ];
                }
                return [];
            }
            else if($this->tipo['nombre']=='Graduados')
            {
                return [
                    'tipo.id'=>'exists:tipoofertas,id',
                    'getmunicipio.id'=>'required|exists:municipios,id',
                    'nombre'=>'required|alpha',
                    'vacantes'=>'required|numeric',
                    'fecha_cierre'=>'required|date|after:'.$now,
                    'salario.id'=>'required|exists:salarios,id',
                    'contrato.id'=>'required|exists:contratos,id',
                    'informaticas'=>'string',
                    'getexperiencia.id'=>'required|exists:experiencias,id',
                    'perfil'=>'required|string|min:200',
                    'funciones'=>'required|string|min:200',
                    'observaciones'=>'string',
                ];
            }
            else if($this->tipo['nombre']=='Practicantes')
            {
                if(Auth::user()->getrol->nombre == 'Empresa')
                {
                    return [
                        'jefe.id'=>'required|numeric',
                        'nombre'=>'required|alpha',
                        'getmunicipio.id'=>'required|exists:municipios,id',
                        'vacantes'=>'required|numeric',
                        'fecha_cierre'=>'required|date|after:'.$now,
                        'salud'=>'required|boolean',
                        'arl'=>'required|boolean',
                        'salario'=>'required|numeric',
                        // //'carta'=>'required_if:arl,0|mimes:pdf|max:1024',
                        'perfil'=>'required|string|min:200',
                        'funciones'=>'required|string|min:200',
                        'observaciones'=>'string',
                    ];
                }
                else if(Auth::user()->getrol->nombre == 'Administrador Dippro')
                {
                    return [
                        'jefe.id'=>'required|numeric',
                        'nombre'=>'required|alpha',
                        'getmunicipio.id'=>'required|exists:municipios,id',
                        'vacantes'=>'required|numeric',
                        'salud'=>'required|boolean',
                        'arl'=>'required|boolean',
                        //'carta'=>'required_if:arl,0|mimes:pdf|max:1024',
                        'salario'=>'required|numeric',
                        'perfil'=>'required|string|min:200',
                        'funciones'=>'required|string|min:200',
                        'observaciones'=>'string',
                    ];
                }
            }
            else
            {
                return [
                    'tipo.id'=>'in:@@@',
                ];
            }
            
        }
        else if($sede->getempresa->getestadodipro->nombre == 'ACEPTADA')
        {
            if(Auth::user()->getrol->nombre == 'Empresa')
            {
                return [
                    'jefe.id'=>'required|numeric',
                    'nombre'=>'required|alpha',
                    'getmunicipio.id'=>'required|exists:municipios,id',
                    'vacantes'=>'required|numeric',
                    'fecha_cierre'=>'required|date|after:'.$now,
                    'salud'=>'required|boolean',
                    'arl'=>'required|boolean',
                    //'carta'=>'required_if:arl,0|mimes:pdf|max:1024',
                    'salario'=>'required|numeric',
                    'perfil'=>'required|string|min:200',
                    'funciones'=>'required|string|min:200',
                    'observaciones'=>'string',
                ];
            }
            else if(Auth::user()->getrol->nombre == 'Administrador Dippro')
            {
                return [
                    'jefe.id'=>'required|numeric',
                    'nombre'=>'required|alpha',
                    'getmunicipio.id'=>'required|exists:municipios,id',
                    'vacantes'=>'required|numeric',
                    'salud'=>'required|boolean',
                    'arl'=>'required|boolean',
                    //'carta'=>'required_if:arl,0|mimes:pdf|max:1024',
                    'salario'=>'required|numeric',
                    'perfil'=>'required|string|min:200',
                    'funciones'=>'required|string|min:200',
                    'observaciones'=>'string',
                ];
            }
        }
        else if($sede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            if ($this->getestado['nombre'] == 'Publicada') {
                return [
                    'vacantes'=>'required|numeric',
                    'fecha_cierre'=>'required|date|after:'.$now
                ];
            }
            
            return [
                'tipo.id'=>'exists:tipoofertas,id',
                'nombre'=>'required|alpha',
                'getmunicipio.id'=>'required|exists:municipios,id',
                'vacantes'=>'required|numeric',
                'fecha_cierre'=>'required|date|after:'.$now,
                'salario.id'=>'required|exists:salarios,id',
                'contrato.id'=>'required|exists:contratos,id',
                'informaticas'=>'string',
                'getexperiencia.id'=>'required|exists:experiencias,id',
                'perfil'=>'required|string',
                'funciones'=>'required|string',
                'observaciones'=>'string',
            ];
        }
            
        
    }
    
    public function messages()
    {
        return [
            'jefe.id.required' => 'El campo jefe es obligatorio',
            'carta.required_if' => 'La carta es obligatoria cuando la empresa no paga arl',
            'tipo.id.required' => 'El tipo de oferta es obligatorio',
            'tipo.id.exists' => 'El tipo de oferta es inválida',
            'salario.id.required' => 'El campo salario es obligatorio',
            'salario.required' => 'El campo remuneración es obligatorio',
            'salario.numeric' => 'El campo remuneración debe ser numérico',
            'salario.id.exists' => 'El salario es inválido',
            'contrato.id.required' => 'El campo contrato es obligatorio',
            'contrato.id.exists' => 'El contrato es inválido',
            'fecha_cierre.after' => 'La fecha de cierre debe ser posterior a la fecha actual',
            'getmunicipio.id.required'=>'El campo municipio es obligatorio',
            'getmunicipio.id.exists'=>'El campo municipio es inválido',
            'getexperiencia.id.required'=>'El campo experiencia laboral es obligatorio',
            'getexperiencia.id.exists'=>'El campo experiencia laboral es inválido',
        ];
    }
}
