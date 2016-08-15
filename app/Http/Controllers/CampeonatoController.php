<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CampeonatoController extends Controller
{
       public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	
    	//buscando todas as informacoes dos times
    	$campeonatos = \App\Campeonato::paginate(10);


    	return view('campeonato.index',compact('campeonatos'));
    }

    public function cadastrar(){
    	return view('campeonato.cadastrar');	
    }

   
    public function salvar(\App\Http\Requests\CampeonatoRequest $request){
    	\App\Campeonato::create($request->all());
        

    	\Session::flash('flash_message',[
    		'msg'=>"Cadastro do campeonato realizado com sucesso!!!",
    		'class'=>"alert-success"
    		]);

    	return redirect()->route('campeonato.cadastrar');

    }

    public function editar($id){
    	$campeonato = \App\Campeonato::find($id);
    	if(!$campeonato){
    		\Session::flash('flash_message',[
    		'msg'=>"Não existe esse campeonato cadastrado!!! Deseja cadastrar um novo campeonato?",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('campeonato.cadastrar');
    	}
    	return view('campeonato.editar',compact('campeonato'));
    }

     public function atualizar(Request $request,$id){
    		\App\Campeonato::find($id)->update($request->all());
    	
    		\Session::flash('flash_message',[
    		'msg'=>"Campeonato atualizado com sucesso!!!",
    		'class'=>"alert-success"
    		]);
    		return redirect()->route('campeonato.index');
    	
    }

    public function deletar($id){
    		$campeonato = \App\Campeonato::find($id);

    		//if($time->deletarTime()){
    		//	\Session::flash('flash_message',[
    		//'msg'=>"Registro não pode ser deletado!!!",
    		//'class'=>"alert-danger"
    		//]);
    		//return redirect()->route('time.index');
    	    //}
    		
    		$campeonato->delete();
    	
    		\Session::flash('flash_message',[
    		'msg'=>"Campeonato removido com sucesso!!!",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('campeonato.index');
    	
    }
}
