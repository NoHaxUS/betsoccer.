<?php

namespace App\Http\Controllers;

use App\Telefone;
use App\User;
use App\Http\Requests\TelefoneRequest;
use App\Http\Requests;

class TelefoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $telefones = Telefone::all();
        return view('telefone.index', ['telefones' => $telefones]);

    }

    public function cadastrar()
    {
        $users = User::all()->pluck('name', 'id');
        return view('telefone.cadastrar', compact('users'));
    }

    public function salvar(TelefoneRequest $request)
    {
        Telefone::create($request->all());
        return redirect()->route('telefone.index');
    }

    public function editar($id)
    {
        $telefone = Telefone::find($id);
        $users = User::all()->pluck('name', 'id');
        return view('telefone.editar', compact('telefone', 'users'));
    }

    public function atualizar(TelefoneRequest $request, $id)
    {
        Telefone::find($id)->update($request->all());
        return redirect()->route('telefone.index');
    }

    public function deletar($id)
    {
        Telefone::find($id)->delete();
        return redirect()->route('telefone.index');
    }
}
