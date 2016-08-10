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
    	$jogos = \App\Jogo::paginate(10);
        
    	return view('jogo.index',compact('jogos'));
    }

    public function cadastrar(){
    	return view('jogo.cadastrar');	
    }

    public function salvar(\App\Http\Requests\JogoRequest $request){
    	\App\Jogo::create($request->all());

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
    		'msg'=>"Não existe esse time cadastrado!!! Deseja cadastrar um novo time?",
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

    		if($jogo->deletarTime()){
    			\Session::flash('flash_message',[
    		'msg'=>"Registro não pode ser deletado!!!",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('time.index');
    		}
    		
    		$jogo->delete();
    	
    		\Session::flash('flash_message',[
    		'msg'=>"Time apagado com sucesso!!!",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('jogo.index');
    	
    }
}
