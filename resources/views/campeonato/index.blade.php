@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li class="active">Campeonato</li>
                </ol>

                <div class="panel-body">
                    <p>
                        <a class="btn btn-info" href="{{ route('campeonato.cadastrar') }}">Cadastrar</a>
                    </p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($campeonatos as $campeonato)

                              <tr>
                                <td scope="row">{{ $campeonato->id }}</td>
                                <td>{{ $campeonato->descricao_campeonato }}</td>
                                <td>
                                    <a class="btn btn-default" href="{{ route('campeonato.editar',$campeonato->id) }}">Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('campeonato.deletar',$campeonato->id) }}' : false)">Excluir</a>
                                </td>
                            </tr>
                            
                            @endforeach
                            

                        </tbody>

                    </table>

                    <div align="center">
                        {!! $campeonatos->links() !!}

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
