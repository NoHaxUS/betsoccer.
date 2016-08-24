@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('jogo.index') }}">Jogo</a></li>
                    <li class="active">Cadastrar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('jogo.salvar')}}" method="post">
                        {{ csrf_field() }}
                        
                        <label for="horarios_id">Horário</label>
                        <div class="form-group {{ $errors->has('horarios_id') ? 'has-error' : ''}}">
                        <select id="horarios_id"  name="horarios_id" class="form-control">
                                <option value="null">Selecione</option>
                                    @foreach ($datas as $data)
                                    <option value="{{ $data->id }}"> {!! $data->data !!}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="time_id">Time Casa</label>
                            <select id="time_id"  name="time_id" class="form-control">
                                <option value="null">Selecione</option>
                                    @foreach ($times as $time)
                                    <option value="{{ $time->id }}"> {{ $time->descricao_time }}</option>
                                    @endforeach
                            </select>
                            <label for="timef_id">Time Fora</label>
                            <select id="timef_id"  name="timef_id" class="form-control">
                                <option value="null">Selecione</option>
                                    @foreach ($times as $time)
                                    <option value="{{ $time->id }}"> {{ $time->descricao_time }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="input-group {{ $errors->has('valor_casa') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valor_casa">Valor Casa</label>
                            <input type="text" name="valor_casa" class="form-control" placeholder="Insira o valor para o vencedor do time da casa" aria-describedby="sizing-addon2">
                            @if($errors->has('valor_casa'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valor_casa') }} </strong>
                            </span>
                            @endif

                       </div>
                       
                        <div class="input-group {{ $errors->has('valor_empate') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                        	<label for="valor_empate">Valor Emp</label>
                            <input type="text" name="valor_empate" class="form-control" placeholder="Insira o valor para o empate" aria-describedby="sizing-addon2">
                            @if($errors->has('valor_empate'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valor_empate') }} </strong>
                            </span>
                            @endif
                        </div>

                        <div class="input-group {{ $errors->has('valor_fora') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valor_fora">Valor Fora</label>
                            <input type="text" name="valor_fora" class="form-control" placeholder="Insira o valor para o vencedor do time de fora" aria-describedby="sizing-addon2">
                            @if($errors->has('valor_fora'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valor_fora') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('valor_1_2') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valor_1_2">Valor Gol</label>
                            <input type="text" name="valor_1_2" class="form-control" placeholder="Insira o valor para o vencedor de mais de um goal" aria-describedby="sizing-addon2">
                            @if($errors->has('valor_1_2'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valor_1_2') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('valor_dupla') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valor_dupla">Valor Dupla</label>
                            <input type="text" name="valor_dupla" class="form-control" placeholder="Insira o valor para o vencedor do time de fora ou empate" aria-describedby="sizing-addon2">
                            @if($errors->has('valor_dupla'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valor_dupla') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('max_gol_2') ? 'has-error' : ''}}">
                            <span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="max_gol_2">Valor -2.5</label>
                            <input type="text" name="max_gol_2" class="form-control" placeholder="Insira o valor para se a partida tiver no maximo 2 gols" aria-describedby="sizing-addon2">
                            @if($errors->has('max_gol_2'))
                            <span class="help-block">
                                <strong> {{ $errors->first('max_gol_2') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('min_gol_3') ? 'has-error' : ''}}">
                            <span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="min_gol_3">Valor +2.5</label>
                            <input type="text" name="min_gol_3" class="form-control" placeholder="Insira o valor para se a partida tiver no minimo 3 gols ou mais" aria-describedby="sizing-addon2">
                            @if($errors->has('min_gol_3'))
                            <span class="help-block">
                                <strong> {{ $errors->first('min_gol_3') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('ambas_gol') ? 'has-error' : ''}}">
                            <span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="ambas_gol">Valor Ambas</label>
                            <input type="text" name="ambas_gol" class="form-control" placeholder="Insira o valor para se a duas equipes marcarem gol" aria-describedby="sizing-addon2">
                            @if($errors->has('ambas_gol'))
                            <span class="help-block">
                                <strong> {{ $errors->first('ambas_gol') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="campeonatos_id">Descrição do Campeonato</label>
                            <select id="campeonatos_id"  name="campeonatos_id" class="form-control">
                                <option value="null">Selecione</option>
                                    @foreach ($campeonatos as $campeonato)
                                    <option value="{{ $campeonato->id }}"> {{ $campeonato->descricao_campeonato }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                        <button class="btn btn-info">Cadastrar</button>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
