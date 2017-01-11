@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">        

        <ol class="breadcrumb panel-heading">
            <li class="active">Relatorios das apostas efetuadas pelo {{$cambista->name}} </li>            
        </ol>
        <!-- Nav tabs -->
        <div class="container col-md-6">
            <div class="row">
                <form class="form-inline" action="{{ route('aposta.cambista')}}" method="post">
                    {{ csrf_field() }}

                    <select class="form-control special-flexselect" name="cambista">
                        <option value="" disabled selected>Nome do Cambista</option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-info">Buscar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Apostas Ativas</a></li>
          <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Apostas Recebidas</a></li>
          <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Apostas Canceladas</a></li>
          <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Outros</a></li>
      </ul>
  </div>
</div>



<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">     

        <div class="table-responsive col-md-offset-1 col-md-8">
         <h3>Valor total receber do Cambista : R$ {{number_format($receber, 2, ',', '.')}}</h3>
         <h3>Premio total possível a ser Pago : R$ {{number_format($total, 2, ',', '.')}}</h3> 
         <table >
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
                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{$aposta->id}}">
                      Detalhe
                  </button>
              </td>
          </tr>
          <!-- Modal -->
          <div class="modal fade" id="modal-{{$aposta->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Jogos</h4>
                </div>
                <div class="modal-body">
                    @foreach ($aposta->jogo as $key => $jogo)

                    <h4>{{$jogo->time[0]->descricao_time}} x {{$jogo->time[1]->descricao_time}}</h4>
                    <h5>    Palpite..:{{$jogo->pivot->tpalpite}}......Valor..:{{$jogo->pivot->palpite}}
                    </h5>

                    @endforeach 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</tbody>
</table>
</div>


</div>
<div role="tabpanel" class="tab-pane" id="profile">    
    <div class="table-responsive col-md-offset-1 col-md-8 "> 
    <h3>Total recebido do cambista : R$ {{number_format($recebido, 2, ',', '.')}}</h3>
        <table>
            <thead>
                <tr>
                    <th>Cod Aposta</th>
                    <th>Data</th>
                    <th>Valor Apostado</th>
                    <th>Apostador</th>
                    <th>Agente</th>
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
                    <td>{{ $cambista->name}}</td>
                    <td>{{ number_format($premiosPago[$key], 2, ',', '.')}}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{$aposta->id}}">
                          Detalhe
                      </button>

                  </td>
              </tr>

              <!-- Modal -->
              <div class="modal fade" id="modal-{{$aposta->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Jogos</h4>
                    </div>
                    <div class="modal-body">
                        @foreach ($aposta->jogo as $key => $jogo)

                        <h4>{{$jogo->time[0]->descricao_time}} x {{$jogo->time[1]->descricao_time}}</h4>
                        <h5>    Palpite..:{{$jogo->pivot->tpalpite}}......Valor..:{{$jogo->pivot->palpite}}
                        </h5>

                        @endforeach 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>                       
    @endforeach
</tbody>
</table>
</div>
</div>
@endsection