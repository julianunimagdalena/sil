<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\ModalidadEstudiante;
use App\Models\Postulado;

class ActaRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->merge(array('aprobacion_dippro' => $this->aprobacion_dippro === 'true'? true: false)); // (bool) $this->arl
        
        $practica = ModalidadEstudiante::find($this->id);
        
        if($practica->getmodalidad->nombre =="Vinculación laboral")
        {
            $postulado = Postulado::where('idEstudiante', $practica->idEstudiante)
                                  ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                  ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                                  ->first();
                                  
            $oferta = $postulado->getoferta;
        }
            
                              
        // dd($this->all(), $practica, $oferta);
        if(!isset($this->estado) || $this->estado == null)
        {
            return [
                'estado'=>'required|exists:estado_practicas,id',
            ];
        }
        else if($this->estado['nombre'] == 'Aprobada' && $practica->aprobacion_dippro)
        {
            return [
                'tutor.id'=>'required|exists:usuarios,id',
                // 'nombre_arl'=>'required|alpha',
                // 'file'=>'required|mimes:pdf|max:1024',
                'fecha_inicio'=>'required|date',
                'fecha_fin'=>'required|after:'.$this->fecha_inicio,
                // 'aprobacion_dippro'=>'required|in:true',
            ];
        }
        else if($this->estado['nombre'] == 'Aprobada')
        {
            if($practica->getmodalidad->nombre=="Vinculación laboral")
            {
                if($oferta->arl)
                {
                    return [
                        'tutor.id'=>'required|exists:usuarios,id',
                        // 'nombre_arl'=>'required|alpha',
                        // 'file'=>'required|mimes:pdf|max:1024',
                        'fecha_inicio'=>'required|date',
                        'fecha_fin'=>'required|after:'.$this->fecha_inicio,
                        'aprobacion_dippro'=>'required|in:true',
                    ];
                }
                else
                {
                    return [
                        'tutor.id'=>'required|exists:usuarios,id',
                        'nombre_arl'=>'required|alpha',
                        'file'=>'required|mimes:pdf|max:1024',
                        'fecha_inicio'=>'required|date',
                        'fecha_fin'=>'required|after:'.$this->fecha_inicio,
                        'aprobacion_dippro'=>'required|in:true',
                    ];
                }
            }
            else
            {
                return [
                    'tutor.id'=>'required|exists:usuarios,id',
                    'fecha_inicio'=>'required|date',
                    'fecha_fin'=>'required|after:'.$this->fecha_inicio,
                    'aprobacion_dippro'=>'required|in:true',
                ];
            }
                
                
        }
        else if($this->estado['nombre'] == 'Esperando respuesta')
        {
            return [
                'id'=>'required',
            ];
        }
        else 
        {
            return [
                'observaciones'=>'required|string',
            ];
        }
            
    }
    
    public function messages()
    {
        return [
            'tutor.id.required'=>'El campo tutor es obligatorio',
            'tutor.id.exists'=>'El campo tutor es inválido',
            'fecha_fin.after'=>'La fecha de terminación debe ser posterior a la de inicio',
            'aprobacion_dippro.required'=>'Debe autorizar la práctica',
            'aprobacion_dippro.in'=>'Debe autorizar la práctica',
        ];
    }
}
