@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="panel panel-default">
           <ol class="breadcrumb panel-heading">
            <li class="active">Apostas Vencedoras</li>
        </ol>
        <div class="panel-body">
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
                
                @foreach($apostaWins as $key => $aposta)
                <tr>
                    <th>{{$aposta->codigo}}</th>
                    <td>{{$aposta->user->name}}</td>
                    <td>{{$aposta->nome_apostador}}</td>
                    <td>{{$aposta->valor_aposta}}</td>
                    <td>{{number_format($total[$key], 2, ',', '.')}}</td>                        
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
</div>

@endsection