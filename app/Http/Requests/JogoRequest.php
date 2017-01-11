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
        'data.required'=>'Informe uma data para o jogo',
        'campeonatos_id.required'=>'Informe um campeonato',        
        'valor_casa.numeric'=>'Informe um valor númerico',
        'valor_fora.numeric'=>'Informe um valor númerico',
        'valor_empate.numeric'=>'Informe um valor númerico',
        'valor_1_2.numeric'=>'Informe um valor númerico',
        'valor_dupla.numeric'=>'Informe um valor númerico',
        'max_gol_2.numeric'=>'Informe um valor númerico',
        'min_gol_3.numeric'=>'Informe um valor númerico',
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
    	'data'=>'required',
    	'timef_id'=>'different:time_id|required',
    	'time_id'=>'required|different:timef_id',
    	'valor_casa'=>'numeric',
    	'valor_empate'=>'numeric',
    	'valor_fora'=>'numeric',
    	'valor_1_2'=>'numeric',
    	'valor_dupla'=>'numeric',
    	'min_gol_3'=>'numeric',
    	'ambas_gol'=>'numeric',
        'campeonatos_id'=>'required',
        ];
    }
}
