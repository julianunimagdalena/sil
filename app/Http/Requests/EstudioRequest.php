<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EstudioRequest extends Request
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
        $anio = \Carbon\Carbon::now()->year;
        
        return [
            'institucion' => 'required|alpha',
            'titulo' => 'required|alpha',
            'anioGrado' => 'required|numeric|max:'.$anio,
            'getmunicipio.id' => 'required|exists:municipios,id',
            'observaciones' => 'string',
        ];
    }
    
    public function messages()
    {
        return [
            'getmunicipio.id.required' => 'El campo municipio es obligatorio',
            'getmunicipio.id.exists' => 'El campo municipio es invalido',
            ];
    }
}
