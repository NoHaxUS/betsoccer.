@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="table-responsive col-md-offset-2 col-md-8">
                <h1 style="text-align: center;">Lista de Roles Cadastradas</h1>
                <table>
                    <thead>
                    <tr>
                        <th>name</th>
                        <th>Display_name</th>
                        <th>description</th>
                        <th>Acao</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td scope="row">{{ $role->name }}</td>
                            <td>{{ $role->display_name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>
                                <a class="btn btn-default" href="{{ route('role.editar',$role->id) }}">Editar</a>
                                <a class="btn btn-danger"
                                   href="javascript:(confirm('Tem certeza que deseja excluir essa role?')? window.location.href='{{ route('role.deletar',$role->id) }}' : false)">Excluir</a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>

                </table>
                <div align="center">

                </div>
            </div>

        </div>
    </div>
@endsection
