<?php

namespace App\Http\Controllers;
use App\Dispositivo;
use App\User;
use App\Http\Requests\DispositivoRequest;
use App\Http\Requests;

class DispositivoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dispositivos = Dispositivo::all();
        return view('dispositivo.index', ['dispositivos' => $dispositivos]);

    }

    public function cadastrar()
    {
        $users = User::all()->pluck('name', 'id');
        return view('dispositivo.cadastrar', compact('users'));
    }

    public function salvar(DispositivoRequest $request)
    {
        Dispositivo::create($request->all());
        return redirect()->route('dispositivo.index');
    }

    public function editar($id)
    {
        $dispositivo = Dispositivo::find($id);
        $users = User::all()->pluck('name', 'id');
        return view('dispositivo.editar', compact('dispositivo', 'users'));
    }

    public function atualizar(DispositivoRequest $request, $id)
    {
        Dispositivo::find($id)->update($request->all());
        return redirect()->route('dispositivo.index');
    }

    public function deletar($id)
    {
        Dispositivo::find($id)->delete();
        return redirect()->route('dispositivo.index');
    }
}
