@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="panel panel-default">
           <ol class="breadcrumb panel-heading">
            <li class="active">Jogo</li>
        </ol>
        <div class="panel-body">
            <p>
                <a class="btn btn-info" href="{{ route('jogo.cadastrar') }}">Cadastrar</a>
            </p>
        </div>
    </div>
    {{ csrf_field() }}
    @foreach($datas as $d)
    <div >
        <h3 align="center">{{date('d/m/Y',strtotime($d))}}</h3>
    </div>
    @foreach(campsHora($d,$jogos) as $camp)
    <h5>{{$camp}}</h5> 
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Cod</th>
                    <th>Hora</th>
                    <th>Casa</th>
                    <th>R</th>
                    <th></th>
                    <th>R</th>
                    <th>Fora</th>
                    <th>Casa</th>
                    <th>Emp</th>
                    <th>Fora</th>
                    <th>Gol</th>
                    <th>Dupla</th>
                    <th>+2.5</th>
                    <th>-2.5</th>
                    <th>Ambas</th>                    
                    <th>Ação</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($jogos as $jo)
                @if (($camp == $jo->campeonato->descricao_campeonato) && (toData($jo->data) == $d))
                
                <tr>
                    <th scope="row">{{ $jo->codigo }}</th>
                    <td>{{date('H:i', strtotime(toHora($jo->data))) }}</td>
                    <td>{{ $jo->time->get(0)['descricao_time'] }}</td>
                    <td>{{ $jo->r_casa or "?" }}</td>
                    <td>X</td>
                    <td>{{ $jo->r_fora or "?" }}</td>
                    <td>{{ $jo->time->get(1)['descricao_time'] }}</td>
                    <td>{{ $jo->valor_casa }}</td>
                    <td>{{ $jo->valor_empate }}</td>
                    <td>{{ $jo->valor_fora }}</td>
                    <td>{{ $jo->valor_1_2 }}</td>
                    <td>{{ $jo->valor_dupla }}</td>
                    <td>{{ $jo->max_gol_2 }}</td>
                    <td>{{ $jo->min_gol_3 }}</td>
                    <td>{{ $jo->ambas_gol }}</td>
                                       
                    <td>
                        <a class="btn btn-default" href="{{ route('jogo.editar',$jo->id) }}">Editar</a>
                        <a class="btn btn-default" href="{{ route('jogo.editar',$jo->id) }}">AddResult</a>
                        @if($jo->ativo == false)
                        <a class="btn btn-success" href="javascript:(confirm('Ativar esse Jogo')? window.location.href='{{ route('jogo.atides',$jo->id) }}' : false)">Ativar</a>
                        @else
                        <a class="btn btn-danger" href="javascript:(confirm('Desativar esse Jogo')? window.location.href='{{ route('jogo.atides',$jo->id) }}' : false)">Desati.</a>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
    @endforeach


</div>
</div>

@endsection