<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ApostaRequest extends Request
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

            'valor_aposta.required'=>'Digite um valor para sua Aposta',
            'valor_aposta.numeric'=>'Digite apenas valores numericos',
            'nome_apostador.required'=>'Digite o nome do Apostador';
            'cpf.required'=>'digite o cpf do Apostador'

        ]
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'valor_aposta'=>'require|numeric',
        'nome_apostador'=>'require'
        'cpf'=>'required|numeric'
        ];
    }
}
