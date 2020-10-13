<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CalificacionRequest extends Request
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
            'idAsistencia'=>'required|numeric|exists:asistencias,id',
            'valor'=>'required|numeric|min:1|max:5',
            'observaciones'=>'string',
        ];
    }
}
