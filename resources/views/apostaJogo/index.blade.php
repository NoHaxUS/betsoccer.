@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="panel panel-default">
            <ol class="breadcrumb panel-heading">
                <li class="active">Valores a pagar</li>
            </ol>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Cod Aposta</th>
                    <th>Valor Apostado</th>
                    <th>Apostador</th>
                    <th>Total a Pagar</th>
                </tr>
                </thead>
                <tbody>
                {{ csrf_field() }}
                @foreach ($apostas as $aposta):
                <tr>
                    <td>{{ $aposta->id }}</td>
                    <td>{{ $aposta->valor_aposta}}</td>
                    <td>{{ $aposta->nome_apostador}}</td>
                    @foreach ($aposta->jogo()->get() as $jogo):
                    <td>{{ $jogo->pivot->palpite * $aposta->valor_aposta}}</td>
                    @endforeach;
                </tr>
                @endforeach;
                </tbody>
            </table>
        </div>


    </div>
</div>

@endsection