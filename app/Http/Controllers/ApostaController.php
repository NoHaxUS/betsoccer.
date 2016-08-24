<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ApostaController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	
    	//buscando todas as informacoes dos times
    	//$aposta = \App\Aposta::paginate(10);
    	$jogos = \App\Jogo::all();
    	return view('aposta.index',compact('jogos'));
    }
    public function cadastrar(){
        $time = \App\Jogo::all();

        return view('aposta.cadastrar',compact('time'));
    }

    public function salvar(\App\Http\Requests\ApostaRequest $request){

        $aposta = \App\Aposta::create($request->all());
        $jogo = $request->get('jogo');
        if ($jogo != null):                                   //Verifica se relação de jogo não é nula
            $aposta->jogo()->sync($jogo);                     //Relaciona jogo com Aposta
        endif;
        return redirect('aposta.index');    

    }

}
