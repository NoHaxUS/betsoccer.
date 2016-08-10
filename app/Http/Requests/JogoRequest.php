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
            'horario.required'=>'Informe a descricao do campeonato',
            'horario.max'=>'O campo deve ter no máximo 5 caracteres',
            'horario.date_format'=>'Informe o horario da seguinte forma xx:xx',
            'valorcasa.required'=>'Informe o valor a pagar no time da casa',
            'valorcasa.max'=>'O campo deve conter no máximo 10 caracteres',
            'valorcasa.numeric'=>'Informe um valor númerico',
            'valoremp.required'=>'Informe o valor a pagar no empate',
            'valoremp.max'=>'O campo deve conter no máximo 10 caracteres',
            'valoremp.numeric'=>'Informe um valor númerico',
            'valorfora.required'=>'Informe o valor a pagar no time de fora',
            'valorfora.max'=>'O campo deve conter no máximo 10 caracteres',
            'valorfora.numeric'=>'Informe um valor númerico',
            'valorgoal.required'=>'Informe o valor a pagar no número de gol',
            'valorgoal.max'=>'O campo deve conter no máximo 10 caracteres',
            'valorgoal.numeric'=>'Informe um valor númerico',
            'valordupla.required'=>'Informe o valor a pagar no empate ou na vitória do time de fora',
            'valordupla.max'=>'O campo deve conter no máximo 10 caracteres',
            'valordupla.numeric'=>'Informe um valor númerico',
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
            'horario'=>'required|max:50|date_format:H:i',
            'valorcasa'=>'required|max:15|numeric',
            'valoremp'=>'required|max:15|numeric',
            'valorfora'=>'required|max:15|numeric',
            'valorgoal'=>'required|max:15|numeric',
            'valordupla'=>'required|max:15|numeric',
        ];
    }
}
