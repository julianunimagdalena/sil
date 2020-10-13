<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IdiomaRequest extends Request
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
            'getidioma.id'=>'required|exists:idiomas,id',
            'getnivellectura.id'=>'required|exists:niveles_idiomas,id',
            'getnivelescritura.id'=>'required|exists:niveles_idiomas,id',
            'getnivelhabla.id'=>'required|exists:niveles_idiomas,id',
            //
        ];
    }
    
    public function messages()
    {
        return [
            'getidioma.id.required' => 'El campo idioma es obligatorio',
            'getidioma.id.exists' => 'El campo idioma es invalido',
            'getnivelescritura.id.required' => 'El nivel de escritura es obligatorio',
            'getnivelescritura.id.exists' => 'El nivel de escritura es invalido',
            'getnivellectura.id.required' => 'El nivel de lectura es obligatorio',
            'getnivellectura.id.exists' => 'El nivel de lectura es invalido',
            'getnivelhabla.id.required' => 'El nivel de habla es obligatorio',
            'getnivelhabla.id.exists' => 'El nivel de habla es invalido',
        ];
    }
}
