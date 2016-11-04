<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\jogo;
use Carbon\Carbon;
use DB;

class ApostaController extends Controller
{
  public function __construct()
  {
    //$this->middleware('auth');
  }
  public function index(){      
        /*buscando todas as informacoes dos times
        foreach ($aposta->jogo as $key) {
               echo "JOGO" . $key->time;
               echo $key->pivot->palpite;
             }
        $aposta = \App\Aposta::paginate(10); 
      */    
        $results = DB::select('select DISTINCT  CAST(data AS date) AS dataS , campeonatos_id from jogos order by data');
        $apostas = \App\Aposta::with('jogo')->get();
     //dd($apostas);
        foreach ($apostas as $aposta) {            
          echo $aposta->jogo->get('id');
        } 
        $campeonatos = \App\Campeonato::all();    
        $jogos =\App\Jogo::all();
        $res= array_merge($results,$jogos->toArray(),$campeonatos->toArray()); 
        return response()->json($res);
        return view('aposta.index',compact('jogos','campeonatos','results'));
      }

      public function cadastrar(){

        $time= \App\Jogo::all();
        return view('aposta.cadastrar',compact('time'));
      }

      public function salvar(\App\Http\Requests\ApostaRequest $request){                

        $jogo=[]; 
        $palpite=[];        
        $jogo = $request->get('jogo');
     // dd($request->all());   
        $aposta = \App\Aposta::create($request->all());        
        foreach ($jogo as $jogos => $value) {    
          $jo = \App\Jogo::find($value);
          $text="palpite";            
          $text.=$value;
          $consu=$request->get($text);      
          $palpite ['palpite']= $jo->$consu;
          $palpite ['tpalpite']=$consu;   
          $aposta->jogo()->attach($value,$palpite); 
        }                             
        $aposta->save();
        \Session::flash('flash_message',[
          'msg'=>"Aposta realizada com Sucesso",
          'class'=>"alert-danger"
          ]);                               
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