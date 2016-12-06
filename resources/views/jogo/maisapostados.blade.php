@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li class="active">Jogos que receberam mais apostas</li>
                </ol>
            </div>
            {{ csrf_field() }}
            @foreach($jogos as $indice => $jogo)
                <h4>{{$jogo->time[0]->descricao_time}} x {{$jogo->time[1]->descricao_time}}</h4>
                <h4>{{'Total de apostas: '.$jogo->apostas()->count()}} </h4>
                <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th>Palpite</th>
                            <th>Quantidade de apostas</th>
                            <th>Valor total</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($palpites[$indice] as $key => $palpite)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$palpite['qtd_'.$key]}}</td>
                                <td>{{number_format($palpite['total_'.$key], 2, ',', '.')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
@endsection