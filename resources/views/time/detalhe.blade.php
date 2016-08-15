@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('time.index') }}">Times</a></li>
                    <li class="active">Detalhe</li>
                </ol>
                <div class="panel-body">
                   <p><b>Codigo: </b>{{ $time->id }}</p>
                   <p><b>Time: </b>{{ $time->descricao_time }}</p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                            <tr>
                                <!-- <th scope="row">{{ $time->campeonatos->id }}</th>
                                <td>{{ $time->campeonatos->descricao_campeonato }}</td> -->
                                <td>
                                    <a class="btn btn-default" href="{{ route('time.editar',$time) }}">Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{ route('time.deletar',$time) }}' : false)">Deletar</a>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <p>
                        <a class="btn btn-info" href="{{ route('time.cadastrar')}}">Cadastrar Campeonatos</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
