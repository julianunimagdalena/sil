<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\Estudiante;
use App\Models\Postulado;
use Auth;

class LegalizarRequest extends Request
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
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $seleccionado = Postulado::where('idEstudiante', $estudiante->id)
                                 ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                 ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                                 ->first();
                                 
        $oferta = $seleccionado->getoferta;
        if($oferta->arl)
        {
            return [
                'nombre_arl'=>'required|alpha',
                'certificado_arl'=>'required|mimes:pdf|max:1024',
                'certificado_salud'=>'required|mimes:pdf|max:1024',
                'horario'=>'required|string',
                'aprobacion_estudiante'=>'required|in:on',
            ];
        }
        else
        {
            return [
                'certificado_salud'=>'required|mimes:pdf|max:1024',
                'horario'=>'required|string',
                'aprobacion_estudiante'=>'required|in:on',
            ];
        }
            
    }
    
    public function messages()
    {
        return [
            'aprobacion_estudiante.in'=>'Debe confirmar la información de su proceso de prácticas',
        ];
    }
}
