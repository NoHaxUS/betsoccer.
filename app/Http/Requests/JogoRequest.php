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
        'valor_casa.different'=>'Palpites Com valores Iguais',
        'valor_fora.numeric'=>'Informe um valor númerico',
        'valor_fora.different'=>'Palpites Com valores Iguais',
        'valor_empate.numeric'=>'Informe um valor númerico',
        'valor_empate.different'=>'Palpites Com valores Iguais',
        'valor_1_2.numeric'=>'Informe um valor númerico',
        'valor_1_2.different'=>'Palpites Com valores Iguais',
        'valor_dupla.numeric'=>'Informe um valor númerico',
        'valor_dupla.different'=>'Palpites Com valores Iguais',            
        'max_gol_2.numeric'=>'Informe um valor númerico',
        'max_gol_2.different'=>'Palpites Com valores Iguais',
        'min_gol_3.numeric'=>'Informe um valor númerico',
        'min_gol_3.different'=>'Palpites Com valores Iguais',
        'ambas_gol.numeric'=>'Informe um valor númerico',
        'ambas_gol.different'=>'Palpites Com valores Iguais',

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
    	'valor_casa'=>'different:valor_empate|different:valor_fora|different:valor_1_2|different:valor_dupla|different:max_gol_2|different:min_gol_3|different:ambas_gol|numeric',
    	'valor_empate'=>'different:valor_casa|different:valor_fora|different:valor_1_2|different:valor_dupla|different:max_gol_2|different:min_gol_3|different:ambas_gol|numeric',
    	'valor_fora'=>'different:valor_casa|different:valor_empate|different:valor_1_2|different:valor_dupla|different:max_gol_2|different:min_gol_3|different:ambas_gol|numeric',
    	'valor_1_2'=>'different:valor_casa|different:valor_fora|different:valor_empate|different:valor_dupla|different:max_gol_2|different:min_gol_3|different:ambas_gol|numeric',
    	'valor_dupla'=>'different:valor_casa|different:valor_fora|different:valor_empate|different:valor_1_2,max_gol_2|different:min_gol_3|different:ambas_gol|numeric',
    	'max_gol_2'=>'different:valor_casa|different:valor_fora|different:valor_empate|different:valor_1_2|different:valor_dupla|different:min_gol_3|different:ambas_gol|numeric',
    	'min_gol_3'=>'different:valor_casa|different:valor_fora|different:valor_empate|different:valor_1_2|different:valor_dupla|different:max_gol_2|different:ambas_gol|numeric',
    	'ambas_gol'=>'different:valor_casa|different:valor_empate|different:valor_fora|different:valor_1_2|different:valor_dupla|different:max_gol_2|different:min_gol_3|numeric',
        'campeonatos_id'=>'required',
        ];
    }
}
