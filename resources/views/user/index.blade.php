@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="table-responsive col-md-offset-2 col-md-8">
                <h1 style="text-align: center;">Lista de Usuarios Cadastradas</h1>
                <table>
                    <thead>
                    <tr>
                        <th>name</th>
                        <th>E-mail</th>
                        <th>Situacao</th>
                        <th>Supervisor</th>
                        <th>Acao</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td scope="row">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->ativo?'Ativo':'Inativo' }}</td>
                             <td>{{ $user->user?$user->user->name:'' }}</td>
                            <td>
                                @permission(['editar-user','relacionar-user','relacionar-role'])
                                <a class="btn btn-default" href="{{ route('user.editar',$user->id) }}">Editar</a>
                                @endpermission
                                @permission('excluir-user')
                                <a class="btn btn-danger"
                                   href="javascript:(confirm('Tem certeza que deseja excluir esse usuario?')? window.location.href='{{ route('user.deletar',$user->id) }}' : false)">Excluir</a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                    {!! $users->render() !!}
                </table>
                <div align="center">

                </div>
            </div>

        </div>
    </div>
@endsection
