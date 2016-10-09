<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TimeController extends Controller
{
    

      public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	
    	//buscando todas as informacoes dos times
    	$times = \App\Time::paginate(10);
        
    	return view('time.index',compact('times'));
    }

    public function cadastrar(){
    	return view('time.cadastrar');	
    }

    public function detalhe($id){
    	$time = \App\Time::find($id);
    	return view('time.detalhe',compact('time'));
    }

    public function salvar(\App\Http\Requests\TimeRequest $request){
    	\App\Time::create($request->all());

    	\Session::flash('flash_message',[
    		'msg'=>"Cadastro do Time realizado com sucesso!!!",
    		'class'=>"alert-success"
    		]);

    	return redirect()->route('time.cadastrar');

    }

    public function editar($id){
    	$time = \App\Time::find($id);
    	if(!$time){
    		\Session::flash('flash_message',[
    		'msg'=>"Não existe esse time cadastrado!!! Deseja cadastrar um novo time?",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('time.cadastrar');
    	}
    	return view('time.editar',compact('time'));
    }

     public function atualizar(Request $request,$id){
    		\App\Time::find($id)->update($request->all());
    	
    		\Session::flash('flash_message',[
    		'msg'=>"Time atualizado com sucesso!!!",
    		'class'=>"alert-success"
    		]);
    		return redirect()->route('time.index');
    	
    }

    public function deletar($id){
    		$time = \App\Time::find($id);

    		//if($time->deletarTime()){
    		//	\Session::flash('flash_message',[
    		//'msg'=>"Registro não pode ser deletado!!!",
    		//'class'=>"alert-danger"
    		//]);
    		//return redirect()->route('time.index');
    	    //}
    		
    		$time->delete();
    	
    		\Session::flash('flash_message',[
    		'msg'=>"Time apagado com sucesso!!!",
    		'class'=>"alert-danger"
    		]);
    		return redirect()->route('time.index');
    	
    }
}
