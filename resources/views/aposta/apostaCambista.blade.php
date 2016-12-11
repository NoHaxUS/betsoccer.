@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div>
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li class="active">Valores a pagar</li>
                </ol>
                
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Apostas Abertas</a></li>
                  <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Apostas Pagas</a></li>
                  <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Apostas Canceladas</a></li>
                  <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Outros</a></li>
              </ul>
          </div>



          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <h1>Premio total possível a ser Pago : R$ {{number_format($total, 2, ',', '.')}}</h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Cod Aposta</th>
                                <th>Data</th>
                                <th>Valor Apostado</th>
                                <th>Apostador</th>
                                <th>Agente</th>
                                <th>Total a Pagar</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{ csrf_field() }}
                            @foreach ($apostas as $key => $aposta)
                            <tr>
                                <td>{{ $aposta->codigo }}</td>
                                <td>{{date('d/m/Y - H:i', strtotime($aposta->created_at)) }}</td>
                                <td>{{ number_format($aposta->valor_aposta, 2, ',', '.')}}</td>
                                <td>{{ $aposta->nome_apostador}}</td>
                                <td>{{ $aposta->user->name}}</td>
                                <td>{{ number_format($premios[$key], 2, ',', '.')}}</td>
                                <td>

                                    <button class="md-trigger md-setperspective" data-modal="modal-{{$aposta->id}}">Detalhe</button>

                                </td>
                            </tr>
                            <div class="md-modal md-effect-11" id="modal-{{$aposta->id}}">
                                <div class="md-content">
                                    <h3>Detalhes da Aposta</h3>
                                    <div>
                                        @foreach ($aposta->jogo as $key => $jogo)

                                        <h4>{{$jogo->time[0]->descricao_time}} x {{$jogo->time[1]->descricao_time}}</h4>
                                        <h5>    Palpite..:{{$jogo->pivot->tpalpite}}......R$:{{$jogo->pivot->palpite}}
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
            <div role="tabpanel" class="tab-pane" id="profile">
                <h1>Premio total já Pago : R$ {{number_format($totalPago, 2, ',', '.')}}</h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Cod Aposta</th>
                                <th>Data</th>
                                <th>Valor Apostado</th>
                                <th>Apostador</th>
                                <th>Total pago</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{ csrf_field() }}
                            @foreach ($apostasPagas as $key => $aposta)
                            <tr>
                                <td>{{ $aposta->codigo }}</td>
                                <td>{{date('d/m/Y - H:i', strtotime($aposta->created_at)) }}</td>
                                <td>{{ $aposta->valor_aposta}}</td>
                                <td>{{ $aposta->nome_apostador}}</td>
                                <td>{{ number_format($premiosPago[$key], 2, ',', '.')}}</td>
                                <td>

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
            <div role="tabpanel" class="tab-pane" id="messages"><h1>EM CONSTRUÇÂO</h1></div>
            <div role="tabpanel" class="tab-pane" id="settings"><h1>EM CONSTRUÇÂO</h1></div>
        </div>

    </div>
</div>
</div>

@endsection