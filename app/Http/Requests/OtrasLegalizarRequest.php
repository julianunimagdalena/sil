<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use App\Models\Estudiante;

class OtrasLegalizarRequest extends Request
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
        $modalidad = $estudiante->getmodalidades[sizeof($estudiante->getmodalidades) - 1];
        // dd($this->all());
        if($modalidad->nombre == 'Validación')
        {
            return [
                'file_carta_solicitud'=>'required|mimes:pdf|max:1024',
                'file_certificado_laboral'=>'required|mimes:pdf|max:1024',
                'file_existencia_empresa'=>'required|mimes:pdf|max:1024',
                'file_afiliacion_ss'=>'required|mimes:pdf|max:1024',
                'file_contrato'=>'mimes:pdf|max:1024',
            ];
        }
        else if($modalidad->nombre == 'Asesorías pymes')
        {
            return [
                'file_carta_solicitud'=>'required|mimes:pdf|max:1024',
                'file_existencia_empresa'=>'required|mimes:pdf|max:1024',
                'file_carta_colaboracion'=>'required|mimes:pdf|max:1024',
                'file_cedula_relegal'=>'required|mimes:pdf|max:1024',
            ];
        }
        else if($modalidad->nombre == 'Prácticas internacionales')
        {
            return [
                'ciudad.id'=>'required|exists:municipios,id',
                'empresa'=>'required|string',
                'file_carta_solicitud'=>'required|mimes:pdf|max:1024',
                'file_existencia_empresa'=>'required|mimes:pdf|max:1024',
                'file_carta_colaboracion'=>'required|mimes:pdf|max:1024',
                'file_carta_director_programa'=>'required|mimes:pdf|max:1024',
                'file_formato_movilidad'=>'required|mimes:pdf|max:1024',
                'file_pasaporte'=>'required|mimes:pdf|max:1024',
                'file_visa'=>'required|mimes:pdf|max:1024',
                'file_cedula'=>'required|mimes:pdf|max:1024',
                'file_carnet'=>'required|mimes:pdf|max:1024',
                'file_padres'=>'required|mimes:pdf|max:1024',
                'file_estudiante'=>'required|mimes:pdf|max:1024',
                'file_itinerario'=>'required|mimes:pdf|max:1024',
                'file_seguro'=>'required|mimes:pdf|max:1024',
            ];
        }
        else if($modalidad->nombre == 'Semestre en el exterior')
        {
            return [
                'ciudad.id'=>'required|exists:municipios,id',
                'empresa'=>'required|string',
                'file_carta_solicitud'=>'required|mimes:pdf|max:1024',
                'file_carta_colaboracion'=>'required|mimes:pdf|max:1024',
                'file_carta_director_programa'=>'required|mimes:pdf|max:1024',
                'file_formato_movilidad'=>'required|mimes:pdf|max:1024',
                'file_pasaporte'=>'required|mimes:pdf|max:1024',
                'file_visa'=>'required|mimes:pdf|max:1024',
                'file_cedula'=>'required|mimes:pdf|max:1024',
                'file_carnet'=>'required|mimes:pdf|max:1024',
                'file_padres'=>'required|mimes:pdf|max:1024',
                'file_estudiante'=>'required|mimes:pdf|max:1024',
                'file_itinerario'=>'required|mimes:pdf|max:1024',
                'file_seguro'=>'required|mimes:pdf|max:1024',
            ];
        }
        else if($modalidad->nombre == 'Práctica social')
        {
            return [
                'file_carta_solicitud'=>'required|mimes:pdf|max:1024',
            ];
        }
        else if($modalidad->nombre == 'Prácticas de empresarismo')
        {
            return [
                'file_carta_solicitud'=>'required|mimes:pdf|max:1024',
                'file_existencia_empresa'=>'required|mimes:pdf|max:1024',
                'file_afiliacion_ss'=>'required|mimes:pdf|max:1024',
            ];
        }
        else
        {
            return [
                'hola'=>'required'
            ];
        }
        
    }
    
    public function messages()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $modalidad = $estudiante->getmodalidades[sizeof($estudiante->getmodalidades) - 1];
        
        if($modalidad->nombre == 'Prácticas internacionales')
        {
            return [
                'ciudad.id.required'=>'La ciudad es obligatoria',
                'ciudad.id.exists'=>'Ciudad inválida',
                'empresa.required'=>'El campo empresa es obligatorio',
                'empresa.string'=>'El campo empresa es inválido',
            ];
        }
        else if($modalidad->nombre == 'Semestre en el exterior')
        {
            return [
                'ciudad.id.required'=>'La ciudad es obligatoria',
                'ciudad.id.exists'=>'Ciudad inválida',
                'empresa.required'=>'El campo universidad es obligatorio',
                'empresa.string'=>'El campo universidad es inválido',
            ];
        }
    }
}
