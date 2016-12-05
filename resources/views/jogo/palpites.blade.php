@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li class="active">Palpites</li>
                </ol>
                <h4>{{$jogo->time[0]->descricao_time}} x {{$jogo->time[1]->descricao_time}}</h4>
            </div>
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
                    {{ csrf_field() }}
                    @foreach($palpites as $key => $palpite)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$palpite['qtd_'.$key]}}</td>
                            <td>{{number_format($palpite['total_'.$key], 2, ',', '.')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection