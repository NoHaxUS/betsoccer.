<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $horario = \App\Horario::paginate(10);
        
        return view('horario.index',compact('horario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cadastrar()
    {
        //
        return view('horario.cadastrar');
    }

    /**
     * Cadastrando Novo Horario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvar(Request $request)
    {
        //
        \App\Horario::create($request->all());

        \Session::flash('flash_message',[
            'msg'=>"Cadastro do Horario realizado com sucesso!!!",
            'class'=>"alert-success"
            ]);

        return redirect()->route('horario.cadastrar');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $horario = \App\Horario::find($id);
        if (!$horario){
            \Session::flash('flash_message',[
            'msg'=>"Não existe esse horario cadastrado!!! Deseja cadastrar um novo horario?",
            'class'=>"alert-danger"
            ]);
            return redirect()->route('horario.cadastrar');
        }
        return view('horario.editar',compact('horario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(Request $request, $id)
    {
        //
            \App\Horario::find($id)->update($request->all());
        
            \Session::flash('flash_message',[
            'msg'=>"Horario atualizado com sucesso!!!",
            'class'=>"alert-success"
            ]);
            return redirect()->route('horario.cadastrar');
    }
     public function detalhe($id){
        $time = \App\Horario::find($id);
        return view('horario.detalhe',compact('horario'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletar($id)
    {
        $hora = \App\Horario::find($id);
        $hora->delete();
        \Session::flash('flash_message',[
            'msg'=>"Horario apagado com sucesso!!!",
            'class'=>"alert-danger"
            ]);
            return redirect()->route('horario.index');
        
    }
}
