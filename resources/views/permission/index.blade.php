@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="table-responsive col-md-offset-2 col-md-8">
                <h1 style="text-align: center;">Lista de Permissions Cadastradas</h1>
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
                    @foreach($permissions as $permission)
                        <tr>
                            <td scope="row">{{ $permission->name }}</td>
                            <td>{{ $permission->display_name }}</td>
                            <td>{{ $permission->description }}</td>
                            <td>
                                <a class="btn btn-default" href="{{ route('permission.editar',$permission->id) }}">Editar</a>
                                <a class="btn btn-danger"
                                   href="javascript:(confirm('Tem certeza que deseja excluir essa permission?')? window.location.href='{{ route('permission.deletar',$permission->id) }}' : false)">Excluir</a>
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
