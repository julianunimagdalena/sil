<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RenovarRequest extends Request
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
            'file_renovacion'=>'required|mimes:pdf|max:1024',
            'fecha'=>'required|date',
            'descripcion'=>'required|string',
        ];
    }
    
    public function messages()
    {
        return[
            'file_renovacion.required'=>'El acta de renovación es obligatoria',
            'file_renovacion.mimes'=>'El acta de renovación debe ser un archivo de formato PDF y pesar máximo 1MB',
            'file_renovacion.max'=>'El acta de renovación debe ser un archivo de formato PDF y pesar máximo 1MB',
        ];
    }
}
