<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class JogoRequest extends Request
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
            'time_casa.required'=>'Informe qual será o time mandante',
            'time_fora.required'=>'Informe qual será o time visitante',
            'valor_casa.required'=>'Informe o valor a pagar no time mandante',
            'valor_casa.max'=>'O campo deve conter no máximo 3 caracteres',
            'valor_casa.numeric'=>'Informe um valor númerico',
            'valor_fora.required'=>'Informe o valor a pagar no time visitante',
            'valor_fora.max'=>'O campo deve conter no máximo 3 caracteres',
            'valor_fora.numeric'=>'Informe um valor númerico',
            'valor_empate.required'=>'Informe o valor a pagar no empate',
            'valor_empate.max'=>'O campo deve conter no máximo 3 caracteres',
            'valor_empate.numeric'=>'Informe um valor númerico',
            'valor_gol.required'=>'Informe o valor a pagar no número de gol',
            'valor_gol.max'=>'O campo deve conter no máximo 3 caracteres',
            'valor_gol.numeric'=>'Informe um valor númerico',
            'valor_dupla.required'=>'Informe o valor a pagar no empate ou na vitória do time de fora',
            'valor_dupla.max'=>'O campo deve conter no máximo 3 caracteres',
            'valor_dupla.numeric'=>'Informe um valor númerico',            
            'max_gol_2.required'=>'Informe o valor a pagar',
            'max_gol_2.max'=>'O campo deve conter no máximo 3 caracteres',
            'max_gol_2.numeric'=>'Informe um valor númerico',
            'min_gol_3.required'=>'Informe o valor a pagar',
            'min_gol_3.max'=>'O campo deve conter no máximo 3 caracteres',
            'min_gol_3.numeric'=>'Informe um valor númerico',
            'ambas_gol.required'=>'Informe o valor a pagar',
            'ambas_gol.max'=>'O campo deve conter no máximo 3 caracteres',
            'ambas_gol.numeric'=>'Informe um valor númerico',

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
            'valor_casa'=>'required|max:3|numeric',
            'valor_empate'=>'required|max:3|numeric',
            'valor_fora'=>'required|max:3|numeric',
            'valor_gol'=>'required|max:3|numeric',
            'valor_dupla'=>'required|max:3|numeric',
            'max_gol_2'=>'required|max:3|numeric',
            'min_gol_3'=>'required|max:3|numeric',
            'ambas_gol'=>'required|max:3|numeric',
        ];
    }
}
