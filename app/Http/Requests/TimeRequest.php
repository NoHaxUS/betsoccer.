<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TimeRequest extends Request
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

    public function messages(){
        return [
            'descricao_time.required'=>'Informe a descricao do time',
            'descricao_time.max'=>'A descrição do time deve ter no maximo 30 caracteres',
            'descricao_time.unique'=>'Time já está Cadastrado!! Insira outro time',

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descricao_time'=>'required|max:30|unique:times,descricao_time',
        ];
    }
}
