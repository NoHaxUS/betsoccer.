<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Http\Requests;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(config('constantes.paginacao'));
        return view('user.index', ['users' => $users]);
    }

    public function cadastrar()
    {
        $roles = Role::all();
        $users = User::orderBy('name')->pluck('name', 'id');
        $users->prepend('Nenhum',0);
        return view('user.cadastrar', compact('roles', 'users'));
    }

    public function salvar(UserRequest $request)
    {
        $this->validate($request,
            ['email' => 'unique:users,email',
                'codigo_seguranca' => 'unique:users,codigo_seguranca',
            'password'=>'required|confirmed'],
            ['unique'=>'O valor indicado para o campo :attribute ja se encontra utilizado.',
            'required'=>'Precisa informar :attribute.',
                'confirmed' => 'A confirmacao para o campo :attribute nao coincide.'],
            ['password'=>'senha']);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user = User::create(
            ['name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'codigo_seguranca' => $request->codigo_seguranca,
                'users_id'=>$request->users_id,
                'ultimo_pagamento' => \Carbon\Carbon::now()]
        );
        $user->attachRoles($roles);
        return redirect()->route('user.index');
    }

    public function editar($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $users = User::orderBy('name')->pluck('name', 'id');
        $users->prepend('Nenhum',0);
        return view('user.editar', compact('user', 'roles','users'));
    }

    public function atualizar(UserRequest $request, $id)
    {
        $this->validate($request,
            ['email' => 'unique:users,email,' . $id,
                'codigo_seguranca' => 'unique:users,codigo_seguranca,' . $id],
            ['unique'=>'O valor indicado para o campo :attribute ja se encontra utilizado.']);
        $user = User::find($id);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->codigo_seguranca = $request->codigo_seguranca;
        if (!empty($request->password)):
            $user->password = bcrypt($request->password);
        endif;
        if (!empty($request->users_id)):
            $user->users_id = $request->users_id;
        endif;
        $user->save();
        $user->roles()->sync($roles);
        return redirect()->route('user.index');
    }

    public function deletar($id)
    {
        User::find($id)->delete();
        return redirect()->route('user.index');
    }
}