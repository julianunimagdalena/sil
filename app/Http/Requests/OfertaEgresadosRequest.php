<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class OfertaEgresadosRequest extends Request
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
            'empresa' => 'required',
            'correo_empresa' => 'required|email',
            'nombre' => 'required',
            'programas' => 'required|array',
            'vacantes' => 'required|numeric',
            'fecha_cierre' => 'required|date',
            'salario' => 'exists:salarios,id',
            'tipo_contrato' => 'exists:contratos,id',
            'experiencia' => 'exists:experiencias,id',
            'municipio' => 'required|exists:municipios,id',
            'perfil' => 'required',
            'funciones' => 'required'
        ];
    }

    public function messages () {
        return [
            'required' => 'Campo obligatorio'
        ];
    }
}
