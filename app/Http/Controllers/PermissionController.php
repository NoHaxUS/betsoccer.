<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index', ['permissions' => $permissions]);
    }

    public function cadastrar()
    {
        return view('permission.cadastrar');
    }

    public function salvar(PermissionRequest $request)
    {
        $this->validate($request,
            ['name' => 'unique:permissions,name'],
            ['unique' => 'O valor indicado para o campo :attribute ja se encontra utilizado.']);
        Permission::create($request->all());
        return redirect()->route('permission.index');
    }

    public function editar($id)
    {
        $permission = Permission::find($id);
        return view('permission.editar', compact('permission'));

    }

    public function atualizar(PermissionRequest $request, $id)
    {
        $this->validate($request,
            ['name' => 'unique:permissions,name,'.$id],
            ['unique' => 'O valor indicado para o campo :attribute ja se encontra utilizado.']);
        Permission::find($id)->update($request->all());
        return redirect()->route('permission.index');
    }

    public function deletar($id)
    {
        Permission::find($id)->delete();
        return redirect()->route('permission.index');
    }
}
