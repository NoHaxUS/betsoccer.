<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DispositivoRequest extends Request
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
            'mac' => 'required|regex:([0-9A-Fa-f]{2}[-][0-9A-Fa-f]{2}[-][0-9A-Fa-f]{2}[-][0-9A-Fa-f]{2}[-][0-9A-Fa-f]{2}[-][0-9A-Fa-f]{2})',
            'user_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'required' => "Precisa informar :attribute.",
            'regex' => 'Formato incorreto do campo :attribute.'
        ];
    }

    public function attributes()
    {
        return [
          'mac'=>'MAC',
            'user_id'=>'Usuario'
        ];
    }
}
