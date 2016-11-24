<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Hashids\Hashids;

class JogoController extends Controller
{


  public function __construct()
  {
    $this->middleware('auth');
}

public function index(){

    	//buscando todas as informacoes dos times
 $jogos = \App\Jogo::with('campeonato')->get();
 $campeonatos = \App\Campeonato::all();
 return view('jogo.index',compact('jogos','campeonatos'));
}

public function cadastrar(){
 $campeonatos = \App\Campeonato::all();
 $times = \App\Time::all();
 return view('jogo.cadastrar',compact('campeonatos','datas','times'));
}

public function salvar(\App\Http\Requests\JogoRequest $request){
    	//dd($request);
  $jogo = \App\Jogo::create($request->all());
  $hashids = new Hashids('betsoccer', 5);
  $jogo->codigo=$hashids->encode($jogo->id);
  $jogo->save();
  $time=[];
  $time []= $request->get('time_id');
  $time []= $request->get('timef_id');
  $jogo->time()->attach($time);

  \Session::flash('flash_message',[
    'msg'=>"Cadastro do Jogo realizado com sucesso!!!",
    'class'=>"alert-success"
    ]);

  return redirect()->route('jogo.cadastrar');

}

public function allJogosPlacar(){
  $jogos = \App\Jogo::with('time', 'campeonato')
  ->where( 'data','<',Carbon::now())
  ->get();
  return view('jogo.resultado',compact('jogos'));
}

public function addPlacar(Request $request){
   $jogo = [];
   $jogo = $request->get('jogo');
   foreach ($jogo as $id) {
      $jogo=\App\Jogo::find($id);
      $jogo->r_casa=$request->get("r_casa".$id);
      $jogo->r_fora=$request->get("r_fora".$id);
      $jogo->save();
  }
  \Session::flash('flash_message',[
    'msg'=>"Placares Adicionados Com Sucesso!!!",
    'class'=>"alert-success"
    ]);
  return redirect()->route('jogo.index');
}

public function editar($id){
   $jogo = \App\Jogo::find($id);
   $campeonatos = \App\Campeonato::all();
   $times = \App\Time::all();
   if(!$jogo){
      \Session::flash('flash_message',[
        'msg'=>"Não existe esse jogo cadastrado!!! Deseja cadastrar um novo Jogo?",
        'class'=>"alert-danger"
        ]);
      return redirect()->route('jogo.cadastrar');
  }
  return view('jogo.editar',compact('jogo','campeonatos','datas','times'));
}

public function atualizar(\App\Http\Requests\JogoRequest $request, $id){
  \App\Jogo::find($id)->update($request->all());

  \Session::flash('flash_message',[
    'msg'=>"Jogo atualizado com sucesso!!!",
    'class'=>"alert-success"
    ]);
  return redirect()->route('jogo.index');

}
public function atiDes($id){
   $jogo = \App\Jogo::find($id);
   $boolean = $jogo->ativo;
   $jogo->ativo=!$boolean;
   $jogo->save();
   return redirect()->route('jogo.index');
}

public function deletar($id){
  $jogo = \App\Jogo::find($id);
  $time=[];
  $time []= $jogo->time->get(0);
  $time []= $jogo->time->get(1);
  /*if($jogo->deletarTime()){
                \Session::flash('flash_message',[
            'msg'=>"Registro não pode ser deletado!!!",
            'class'=>"alert-danger"
            ]);
            return redirect()->route('time.index');
            }
    */
            $jogo->time()->detach($time);
            $jogo->delete();

            \Session::flash('flash_message',[
              'msg'=>"Jogo apagado com sucesso!!!",
              'class'=>"alert-danger"
              ]);
            return redirect()->route('jogo.index');

        }
    }
