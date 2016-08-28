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
        
        $time= \App\Jogo::all();
        return view('aposta.cadastrar',compact('time'));
    }
    public function salvar(\App\Http\Requests\ApostaRequest $request){                
        $jogo=[]; 
        $palpite=[];        
        $jogo = $request->get('jogo');   
        $aposta = \App\Aposta::create($request->all());        
        foreach ($jogo as $jogos => $value) {          
            $text="palpite";            
            $text.=$value;            
            $palpite ['palpite']= $request->get($text);   
            $aposta->jogo()->attach($value,$palpite); 
        }                             
        $aposta->save();                               
        return redirect()->route('aposta.cadastrar');    
    }  
}