@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="table-responsive col-md-offset-2 col-md-8">
                <h1 style="text-align: center;">Lista de Telefones Cadastrados</h1>
                <a href="{{ route('telefone.cadastrar')}}" class="btn btn-primary light-blue darken-3">Cadastrar</a>
                <table>
                    <thead>
                    <tr>
                        <th>DDD</th>
                        <th>Numero</th>
                        <th>Usuario</th>
                        <th>Acao</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($telefones as $telefone)
                        <tr>
                            <td scope="row">{{ $telefone->ddd }}</td>
                            <td>{{ $telefone->numero }}</td>
                            <td>{{ $telefone->user->name }}</td>
                            <td>
                                <a class="btn btn-default" href="{{ route('telefone.editar',$telefone->id) }}">Editar</a>
                                <a class="btn btn-danger" href="javascript:(confirm('Tem certeza que deseja excluir esse telefone?')? window.location.href='{{ route('telefone.deletar',$telefone->id) }}' : false)">Excluir</a>
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
