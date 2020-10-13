<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ConferenciaRequest extends Request
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
        if($this->id == null)
        {
            return [
                'nombre'=>'required|alpha|unique:conferencia,nombre',
                // 'programas'=>'required|min:1'
            ];
        }
        else
        {
            return [
                'nombre'=>'required|alpha',
                // 'programas'=>'required|min:1'
            ];
        }
            
    }
}
