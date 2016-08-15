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
                                <th>Codigo</th>                                
                                <th>Casa</th>
                                <th>Fora</th>
                                <th>Rs Casa</th>
                                <th>Rs Emp</th>
                                <th>Rs Fora</th>
                                <th>Rs Gol</th>
                                <th>Rs Dupla</th>
                                <th>Rs +2.5</th>
                                <th>Rs -2.5</th>
                                <th>Rs Ambas</th>
                                <th>Campeonato</th>
                                <th>Horário</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($jogos as $jo)
    
                              <tr>
                                <td scope="row">{{ $jo->id }}</td>                                
                                <td>{{ $jo->time_casa }}</td>
                                <td>{{ $jo->time_fora }}</td>
                                <td>{{ $jo->valor_casa }}</td>
                                <td>{{ $jo->valor_empate }}</td>
                                <td>{{ $jo->valor_fora }}</td>
                                <td>{{ $jo->valor_gol }}</td>
                                <td>{{ $jo->valor_dupla }}</td>
                                <td>{{ $jo->max_gol_2 }}</td>
                                <td>{{ $jo->min_gol_3 }}</td>
                                <td>{{ $jo->ambas_gol }}</td>
                                <td>{{ $jo->campeonatos_id }}</td>
                                <td>{{ $jo->horario_id }}</td>
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
