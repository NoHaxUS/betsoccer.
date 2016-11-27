<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CampeonatoRequest extends Request
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
            'descricao_campeonato.required'=>'Informe a descricao do campeonato',
            'descricao_campeonato.max'=>'A descrição do time deve ter no maximo 50 caracteres',
            'descricao_campeonato.unique'=>'Campeonato já está Cadastrado!! Insira outro',

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

            'descricao_campeonato'=>'required|max:50|unique:campeonatos,descricao_campeonato'',
        ];
    }
}
