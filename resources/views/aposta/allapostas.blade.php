@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="panel panel-default">
            <ol class="breadcrumb panel-heading">
                <li class="active">Valores a pagar</li>
            </ol>
            <h1>Premio total possível a ser Pago : {{number_format($total, 2, ',', '.')}}</h1>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Cod Aposta</th>
                        <th>Data</th>
                        <th>Valor Apostado</th>
                        <th>Apostador</th>
                        <th>Total a Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    {{ csrf_field() }}
                    @foreach ($apostas as $key => $aposta)
                    <tr>
                        <td>{{ $aposta->codigo }}</td>
                        <td>{{date('d/m/Y - H:i', strtotime($aposta->created_at)) }}</td>
                        <td>{{ $aposta->valor_aposta}}</td>
                        <td>{{ $aposta->nome_apostador}}</td>
                        <td>{{ number_format($premios[$key], 2, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

@endsection