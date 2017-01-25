<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TelefoneRequest extends Request
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
            'ddd' =>'required|digits:2|numeric',
            'numero' =>'required|digits_between:8,9|numeric',
            'user_id' =>'required'
        ];
    }
    public function messages()
    {
        return [
            'required' => "Precisa informar :attribute.",
            'digits_between' => 'O campo :attribute deve conter entre :min e :max algarismos.',
            'numeric' => 'O campo :attribute precisa conter apenas algarismos.',
            'digits' => 'O campo :attribute precisa conter :digits algarismos.',
        ];
    }

    public function attributes()
    {
        return [
            'ddd'=>'DDD',
            'user_id'=>'Usuario'
        ];
    }
}
