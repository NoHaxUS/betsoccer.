<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
 $valores = \App\Valor::all();
    return view('jogo.index',compact('jogos','campeonatos','valores'));
}

public function cadastrar(){
   $campeonatos = \App\Campeonato::all();
   $times = \App\Time::all();
   $valores = \App\Valor::all();
   return view('jogo.cadastrar',compact('campeonatos','datas','times', 'valores'));
}

public function salvar(\App\Http\Requests\JogoRequest $request){
    	//dd($request);
    $jogo = \App\Jogo::create($request->all());
    $jogo->save();
    $time= $request->get('valor_id');
    $jogo->valores()->attach($time);

    \Session::flash('flash_message',[
      'msg'=>"Cadastro do Time realizado com sucesso!!!",
      'class'=>"alert-success"
      ]);

    return redirect()->route('jogo.cadastrar');

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
      'msg'=>"Time atualizado com sucesso!!!",
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
