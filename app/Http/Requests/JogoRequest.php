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
    	'timef_id.different'=>'Informe um time Diferente do time Mandante',
        'timef_id.required'=>'Informe um time',
    	'time_id.required'=>'Informe um time',
        'time_id.different'=>'Informe um time Diferente do time visitante',
    	'horarios_id.required'=>'Informe uma data para o jogo',
        'campeonatos_id.required'=>'Informe um campeonato',        
    	'valor_casa.required'=>'Informe o valor a pagar no time mandante',
    	'valor_casa.max'=>'O campo deve conter no máximo 3 caracteres',
    	'valor_casa.numeric'=>'Informe um valor númerico',
    	'valor_fora.required'=>'Informe o valor a pagar no time visitante',
    	'valor_fora.max'=>'O campo deve conter no máximo 3 caracteres',
    	'valor_fora.numeric'=>'Informe um valor númerico',
    	'valor_empate.required'=>'Informe o valor a pagar no empate',
    	'valor_empate.max'=>'O campo deve conter no máximo 3 caracteres',
    	'valor_empate.numeric'=>'Informe um valor númerico',
    	'valor_1_2.required'=>'Informe o valor a pagar no número de gol',
    	'valor_1_2.max'=>'O campo deve conter no máximo 3 caracteres',
    	'valor_1_2.numeric'=>'Informe um valor númerico',
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
    	'horarios_id'=>'required',
    	'timef_id'=>'different:time_id|required',
    	'time_id'=>'required|different:timef_id',
    	'valor_casa'=>'required|max:15|numeric',
    	'valor_empate'=>'required|max:15|numeric',
    	'valor_fora'=>'required|max:15|numeric',
    	'valor_1_2'=>'required|max:15|numeric',
    	'valor_dupla'=>'required|max:15|numeric',
    	'max_gol_2'=>'required|max:15|numeric',
    	'min_gol_3'=>'required|max:15|numeric',
    	'ambas_gol'=>'required|max:15|numeric',
        'campeonatos_id'=>'required',
    	];
    }
}
