<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CharlaRequest extends Request
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
            'getconferencia.id' =>'required|exists:conferencia,id',
            'getorador.identificacion'=>'required|numeric',
            'getorador.nombres'=>'required|alpha',
            'getorador.apellidos'=>'required|alpha',
            'getorador.correo'=>'required|email',
            'lugar'=>'required|string',
            'fecha'=>'required|date',
            'str_hora_inicial'=>'required|date_format:H:i',
            'str_hora_final'=>'required|date_format:H:i',
            'cupo'=>'required|numeric|min:1',
        ];
    }
    
    public function messages()
    {
        return [
            'getconferencia.id.required'=>'El campo conferencia es obligatorio',
            'getconferencia.id.exists'=>'El campo conferencia es inválido',
            'getorador.identificacion.required'=>'El campo identificación es obligatorio',
            'getorador.identificacion.numeric'=>'El campo identificación debe ser numérico',
            'getorador.nombres.required'=>'El campo nombres es obligatorio',
            'getorador.nombres.alpha'=>'El campo nombres debe contener solo letras',
            'getorador.apellidos.required'=>'El campo apellidos es obligatorio',
            'getorador.apellidos.alpha'=>'El campo apellidos debe contener solo letras',
            'getorador.correo.required'=>'El campo correo es obligatorio',
            'getorador.correo.email'=>'Formato de correo inválido',
            'str_hora_inicial.required'=>'El campo hora inicial es obligatorio',
            'str_hora_inicial.date_format'=>'Formato de hora inválido',
            'str_hora_final.required'=>'El campo hora final es obligatorio',
            'str_hora_final.date_format'=>'Formato de hora inválido',
            'cupo.required'=>'El campo cupo es obligatorio',
            'cupo.numeric'=>'El campo cupo debe ser numérico',
            'cupo.min'=>'El campo cupo debe ser al menos 1',
        ];
    }
}
