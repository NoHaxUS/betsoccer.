@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li class="active">Jogo</li>
                </ol>


                <div class="panel-body">
                    <p>
                        <a class="btn btn-info" href="{{ route('jogo.cadastrar') }}">Cadastrar</a>
                    </p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Horário</th>
                                <th>Time Casa</th>
                                <th>Time Fora</th>
                                <th>Valor Casa</th>
                                <th>Valor Emp</th>
                                <th>Valor Fora</th>
                                <th>Valor Gol</th>
                                <th>Valor Dupla</th>
                                <th>Campeonato</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($jogos as $jo)

                              <tr>
                                <td scope="row">{{ $jo->id }}</td>
                                <td>{{ $jo->horario }}</td>
                                <td>{{ $jo->timecasa }}</td>
                                <td>{{ $jo->timefora }}</td>
                                <td>{{ $jo->valorcasa }}</td>
                                <td>{{ $jo->valoremp }}</td>
                                <td>{{ $jo->valorfora }}</td>
                                <td>{{ $jo->valorgoal }}</td>
                                <td>{{ $jo->valordupla }}</td>
                                <td>{{ $jo->campeonato_id }}</td>
                                <td>
                                    <a class="btn btn-default" href="{{ route('jogo.editar',$jo->id) }}">Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('jogo.deletar',$jo->id) }}' : false)">Excluir</a>
                                </td>
                            </tr>
                            
                            @endforeach
                            

                        </tbody>

                    </table>

                    <div align="center">
                        {!! $jogos->links() !!}

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
