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
                        @foreach($time->campeonatos as $ca)
                            <tr>
                                <th scope="row">{{ $ca->id }}</th>
                                <td>{{ $ca->descricao_campeonato }}</td>
                                <td>
                                    <a class="btn btn-default" href="{{ route('campeonato.editar',$campeonato->id) }}">Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{ route('campeonato.deletar',$campeonato->id) }}' : false)">Deletar</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                    <p>
                        <a class="btn btn-info" href="{{ route('campeonato.cadastrar')}}">Cadastrar Campeonatos</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
