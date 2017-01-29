<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use App\Http\Requests;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::paginate(config('constantes.paginacao'));
        return view('role.index', ['roles' => $roles]);
    }

    public function cadastrar()
    {
        $permissions = Permission::all();

        return view('role.cadastrar', compact('permissions'));
    }

    public function salvar(RoleRequest $request)
    {
        $this->validate($request,
            ['name' => 'unique:roles,name'],
            ['unique' => 'O valor indicado para o campo :attribute se encontra utilizado.']);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role = Role::create($request->all());
        $role->attachPermissions($permissions);
        return redirect()->route('role.index');
    }

    public function editar($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();

        return view('role.editar', compact('role', 'permissions'));

    }

    public function atualizar(RoleRequest $request, $id)
    {
        $this->validate($request,
            ['name' => 'unique:roles,name,' . $id],
            ['unique' => 'O valor indicado para o campo :attribute se encontra utilizado.']);
        $role = Role::find($id);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->update($request->all());
        $role->savePermissions($permissions);
        return redirect()->route('role.index');
    }

    public function deletar($id)
    {
        Role::where('id', $id)->delete();
        return redirect()->route('role.index');
    }
}
