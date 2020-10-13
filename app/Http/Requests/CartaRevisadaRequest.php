<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CartaRevisadaRequest extends Request
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
        if($this->modelo == 4)
        {
            return [
                'getestado.id'=>'required|exists:estado_cartas,id',
                'radicado'=>'required_if:getestado.id,2|numeric',
                'anio'=>'required_if:getestado.id,2|numeric',
                'periodo'=>'required_if:getestado.id,2|in:I,II',
                'modelo'=>'required_if:getestado.id,2|in:1,2,3,4',
                'promedio'=>'required_if:modelo,4|numeric|min:320|max:500',
            ];
        }
        else
        {
            return [
                'getestado.id'=>'required|exists:estado_cartas,id',
                'radicado'=>'required_if:getestado.id,2|numeric',
                'anio'=>'required_if:getestado.id,2|numeric',
                'periodo'=>'required_if:getestado.id,2|in:I,II',
                'modelo'=>'required_if:getestado.id,2|in:1,2,3,4',
                'promedio'=>'required_if:modelo,2|numeric|min:320|max:500',
            ];
        }
            
    }
    
    public function messages()
    {
        return [
            'getestado.id.required'=>'El campo estado es obligatorio',
            'getestado.id.exists'=>'El campo estado es inválido',
            'anio.required_if'=>'El campo año es obligatorio',
            'anio.numeric'=>'El campo año debe ser numérico',
            'radicado.required_if'=>'El campo radicado es obligatorio',
            'periodo.required_if'=>'El campo periodo es obligatorio',
            'modelo.required_if'=>'El campo tipo de carta es obligatorio',
            'promedio.required_if'=>'El campo promedio es obligatorio',
        ];
    }
}
