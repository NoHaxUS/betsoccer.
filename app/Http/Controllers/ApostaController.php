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
    	$aposta = \App\Aposta::paginate(10);

    	$jogos = \App\Jogo::all();


    	return view('aposta.index',compact('aposta','jogos'));
    }

}
