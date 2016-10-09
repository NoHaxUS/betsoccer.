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
    	/*buscando todas as informacoes dos times
        foreach ($aposta->jogo as $key) {
               echo "JOGO" . $key->time;
               echo $key->pivot->palpite;
             }
    	$aposta = \App\Aposta::paginate(10);
      */      
     $apostas = \App\Aposta::with('jogo','horario')->get();
     //dd($apostas);
     foreach ($apostas as $aposta) {            
      echo $aposta->jogo->get('id');
    }     
    $jogos = \App\Jogo::with('horario')->get();
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
    return redirect()->route('aposta.index');    
  }
  public function show($id){

  }  
  public function showAll(){
    $jogos = \App\Aposta::find(4)->jogo()->get();
    foreach ($jogos as $jogo) {
      dd($jogo->pivot->palpite);
    }
    dd($jogos);
  }
}