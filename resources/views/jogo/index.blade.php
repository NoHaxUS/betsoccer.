@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <p class="text-center"><a class="btn btn-success" href="{{ route('jogo.maisApostados') }}">Jogos com mais apostas</a></p>
        @foreach($datas as $d)
        {{ csrf_field() }}
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
                        <th>+2.5</th>
                        <th>-2.5</th>
                        <th>Dupla</th>
                        <th>Gol</th>
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
                        @if($jo->valor_casa == null || $jo->valor_casa == 0)
                        <td>{{ "--"}}</td>
                        @else
                        <td>{{ $jo->valor_casa}}</td>
                        @endif
                        @if($jo->valor_empate  == null || $jo->valor_empate  == 0 )
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{  $jo->valor_empate }}</td>
                        @endif
                        @if($jo->valor_fora == null || $jo->valor_fora == 0 )
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{$jo->valor_fora}}</td>
                        @endif
                        @if($jo->min_gol_3 == null || $jo->min_gol_3 ==0)
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{$jo->min_gol_3}}</td>
                        @endif
                        @if($jo->max_gol_2 == null || $jo->max_gol_2 ==0)
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{$jo->max_gol_2}}</td>
                        @endif
                        @if($jo->valor_dupla == null || $jo->valor_dupla ==0)
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{$jo->valor_dupla}}</td>
                        @endif
                        @if($jo->valor_1_2 == null || $jo->valor_1_2 == 0)
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{$jo->valor_1_2}}</td>
                        @endif
                        @if($jo->ambas_gol == null || $jo->ambas_gol == 0)
                        <td>{{ "--" }}</td>
                        @else
                        <td>{{$jo->ambas_gol}}</td>
                        @endif
                        <td>
                            @permission('editar-jogo')
                            <a class="btn btn-default" href="{{ route('jogo.editar',$jo->id) }}">Edit</a>
                            @endpermission
                            @permission('editar-placar')
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{$jo->id}}">
                            plac
                            @endpermission

                          </button>
                          @if($jo->ativo == false)
                              @permission('ativar-jogo')
                          <a class="btn btn-success" href="javascript:(confirm('Ativar esse Jogo')? window.location.href='{{ route('jogo.atides',$jo->id) }}' : false)">Ati</a>
                              @endpermission
                          @else
                                @permission('desativar-jogo')
                          <a class="btn btn-danger" href="javascript:(confirm('Desativar esse Jogo')? window.location.href='{{ route('jogo.atides',$jo->id) }}' : false)">Des.</a>
                              @endpermission
                          @endif
                            @permission('consultar-palpite')
                          <a class="btn btn-default" href="{{ route('jogo.totalPalpites',$jo->id) }}">Palpites</a>
                            @endpermission
                      </td>
                  </tr>
                  <!-- Modal -->
                  <div class="modal fade" id="modal-{{$jo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Jogos</h4>
                        </div>
                        <div class="modal-body">
                         <h3>Adcione um placar ao Jogo</h3>
                         <form action="{{ route('jogo.addPlacar') }}" method="post">
                             {{ csrf_field() }}

                             <p>
                              <label>
                                 <input type="checkbox" name="jogo[]" value="{{ $jo->id }}" checked class="hide">
                                 {{ $jo->codigo }}
                             </label>
                             {{ $jo->time->get(0)['descricao_time'] }}
                             <input class="form-group" name="r_casa{{$jo->id}}" value="{{$jo->r_casa or '?'}}" maxlength="2" size="2" required>
                             X
                             <input  class="form-group" name="r_fora{{$jo->id}}" value="{{$jo->r_fora or '?'}}" maxlength="2" size="2" required>
                             {{ $jo->time->get(1)['descricao_time'] }}
                             <button class="btn btn-info">Cadastrar Placar</button>
                         </p>

                     </form>
                 </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
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