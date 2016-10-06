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
                            <th>Cod</th>                                
                            <th>Casa</th>
                            <th>Fora</th>
                            <th>Casa</th>
                            <th>Emp</th>
                            <th>Fora</th>
                            <th>Gol</th>
                            <th>Dupla</th>
                            <th>+2.5</th>
                            <th>-2.5</th>
                            <th>Ambas</th>
                            <th>Campeonato</th>
                            <th>data</th>
                            <th>Ação</th> 
                        </tr>
                    </thead>
                    <tbody>

                        {{ csrf_field() }}
                        @foreach($campeonatos as $camp)
                        @foreach($jogos as $jo)
                        @if ($camp->id == $jo->campeonatos_id)
                        <tr>
                            <td scope="row">{{ $jo->id }}</td>                                                                
                            <td>{{ $jo->time->get(0)['descricao_time'] }}</td>
                            <td>{{ $jo->time->get(1)['descricao_time'] }}</td>
                            <td>{{ $jo->valor_casa }}</td>
                            <td>{{ $jo->valor_empate }}</td>
                            <td>{{ $jo->valor_fora }}</td>
                            <td>{{ $jo->valor_1_2 }}</td>
                            <td>{{ $jo->valor_dupla }}</td>
                            <td>{{ $jo->max_gol_2 }}</td>
                            <td>{{ $jo->min_gol_3 }}</td>
                            <td>{{ $jo->ambas_gol }}</td>
                            <td>{{ \App\Campeonato::find($jo->campeonatos_id)->descricao_campeonato }}</td>
                            <td>{{ \App\Horario::find($jo->horarios_id)->data }}</td>                              
                            <td>
                                <a class="btn btn-default" href="{{ route('jogo.editar',$jo->id) }}">Editar</a>
                                <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('jogo.deletar',$jo->id) }}' : false)">Excluir</a>
                            </td>
                        </tr>
                        @endif  
                        @endforeach
                        @endforeach                            
                    </tbody>
                </table>                   
            </div>
        </div>
    </div>
</div>
</div>

@endsection