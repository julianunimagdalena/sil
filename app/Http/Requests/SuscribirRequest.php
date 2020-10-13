<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SuscribirRequest extends Request
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
        return [
            'fecha_inicial'=>'required|date',
            'fecha_final'=>'required|date|after:'.$this->fecha_inicial,
            'file_convenio'=>'required|mimes:pdf|max:1024',
        ];
    }
    
    public function messages()
    {
        return [
            'fecha_final.after'=>'El campo fecha final debe ser posterior a la fecha inicial',
            'file_convenio.required'=>'El archivo convenio es obligatorio',
            'file_convenio.mimes'=>'El archivo de ser formato PDF',
            'file_convenio.max'=>'El archivo debe pesar máximo 1MB',
        ];
    }
}
