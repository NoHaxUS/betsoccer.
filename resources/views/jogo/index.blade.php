@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($datas as $d)
        {{ csrf_field() }}
        <div >
            <h3 align="center">{{date('d/m/Y',strtotime($d))}}</h3>
            <a class="btn btn-default" href="{{ route('jogo.maisApostados') }}">Palpites</a>
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
                            <a class="btn btn-default" href="{{ route('jogo.editar',$jo->id) }}">Edit</a>
                            <button class="md-trigger btn-danger" data-modal="modal-{{$jo->id}}">Plac</button>
                            @if($jo->ativo == false)
                            <a class="btn btn-success" href="javascript:(confirm('Ativar esse Jogo')? window.location.href='{{ route('jogo.atides',$jo->id) }}' : false)">Ati</a>
                            @else
                            <a class="btn btn-danger" href="javascript:(confirm('Desativar esse Jogo')? window.location.href='{{ route('jogo.atides',$jo->id) }}' : false)">Des.</a>
                            @endif
                            <a class="btn btn-default" href="{{ route('jogo.totalPalpites',$jo->id) }}">Palpites</a>
                        </td>
                    </tr>
                    <div class="md-modal md-effect-11" id="modal-{{$jo->id}}">
                        <div class="md-content">
                            <h3>Detalhes da jo</h3>
                            <div>
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
                         <button class="md-close">FECHAR!</button>
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