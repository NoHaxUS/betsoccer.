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
    	return view('jogo.index',compact('jogos'));
    }

    public function cadastrar(){
       $campeonatos = \App\Campeonato::all();
       $datas = \App\Horario::all();
       $times = \App\Time::all();
    	return view('jogo.cadastrar',compact('campeonatos','datas','times'));	
    }

    public function salvar(\App\Http\Requests\JogoRequest $request){
    	//dd($request);
        //dd($request->session());
        $jogo = \App\Jogo::create($request->all());
        $jogo->save();
        $time=[];
        $time []= $request->get('time_id');
        $time []= $request->get('timef_id');
        $jogo->time()->attach($time);
    	
        \Session::flash('flash_message',[
    		'msg'=>"Cadastro do Time realizado com sucesso!!!",
    		'class'=>"alert-success"
    		]);

    	return redirect()->route('jogo.cadastrar');

    }

    public function editar($id){
    	$jogo = \App\Jogo::find($id);
    	if(!$jogo){
    		\Session::flash('flash_message',[
    		'msg'=>"Não existe esse jogo cadastrado!!! Deseja cadastrar um novo Jogo?",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('jogo.cadastrar');
    	}
    	return view('jogo.editar',compact('jogo'));
    }

     public function atualizar(Request $request,$id){
    		\App\Jogo::find($id)->update($request->all());
    	
    		\Session::flash('flash_message',[
    		'msg'=>"Time atualizado com sucesso!!!",
    		'class'=>"alert-success"
    		]);
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
