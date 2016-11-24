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
                <input type="search" name="busca-aposta">
                <a class="btn btn-info" href="{{ route('jogo.cadastrar') }}">Aposta Detalhada</a>
            </p>
        </div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Cod</th>
                    <th>Cambista</th>
                    <th>Apostardor</th>
                    <th>Valor</th>
                    <th>Retorno</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                {{ csrf_field() }}
                
                @foreach($apostaWins as $aposta)
                <tr>
                    <th>{{$aposta->id}}</th>
                    <td>{{$aposta->user->name}}</td>
                    <td>{{$aposta->nome_apostador}}</td>
                    <td>{{$aposta->valor_aposta}}</td>
                    <td>{{$aposta->valor_aposta}}</td>                        
                    <td>
                        <a class="btn btn-default">Detalhar</a>
                        
                        <a class="btn btn-success">Pagar</a>
                        
                        <button class="md-trigger md-setperspective" data-modal="modal-{{$aposta->id}}">Detalhe</button>
                        
                    </td>
                </tr>
                <div class="md-modal md-effect-11" id="modal-{{$aposta->id}}">
                    <div class="md-content">
                        <h3>Detalhes da Aposta</h3>
                        <div>
                            @foreach ($aposta->jogo as $key => $jogo)
                           
                            <h4>{{$jogo->time[0]->descricao_time}} x {{$jogo->time[1]->descricao_time}}</h4>
                            <h5>    Palpite..:{{$jogo->pivot->tpalpite}}......Valor..:{{$jogo->pivot->palpite}}
                            </h5>
                            
                            @endforeach 

                            <button class="md-close">FECHAR!</button>
                        </div>
                    </div>
                </div>
                @endforeach                                 
            </tbody>
        </table>
    </div>

</div>
</div>

@endsection