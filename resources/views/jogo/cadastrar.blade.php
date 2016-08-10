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
                        <div class="form-group {{ $errors->has('horario') ? 'has-error' : ''}}">
                            <label for="horario">Horário</label>
                            <input type="text" name="horario" class="form-control" placeholder="Insira o horário da partida">
                            @if($errors->has('horario'))
                            <span class="help-block">
                                <strong> {{ $errors->first('horario') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="timecasa">Time Casa</label>
                            <select id="timecasa"  name="timecasa" class="form-control">
                                <option valeu="null">Selecione</option>
                                    @foreach (App\Time::all() as $time)
                                    <option valeu="{{ $time->descricao_time }}"> {{ $time->descricao_time }}</option>
                                    @endforeach
                            </select>
                            <label for="timefora">Time Fora</label>
                            <select id="timefora"  name="timefora" class="form-control">
                                <option valeu="null">Selecione</option>
                                    @foreach (App\Time::all() as $time)
                                    <option valeu="{{ $time->descricao_time }}"> {{ $time->descricao_time }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="input-group {{ $errors->has('valorcasa') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valorcasa">Valor Casa</label>
                            <input type="text" name="valorcasa" class="form-control" placeholder="Insira o valor para o vencedor do time da casa" aria-describedby="sizing-addon2">
                            @if($errors->has('valorcasa'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valorcasa') }} </strong>
                            </span>
                            @endif

                       </div>
                       
                        <div class="input-group {{ $errors->has('valoremp') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                        	<label for="valoremp">Valor Emp</label>
                            <input type="text" name="valoremp" class="form-control" placeholder="Insira o valor para o empate" aria-describedby="sizing-addon2">
                            @if($errors->has('valoremp'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valoremp') }} </strong>
                            </span>
                            @endif
                        </div>

                        <div class="input-group {{ $errors->has('valorfora') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valorfora">Valor Fora</label>
                            <input type="text" name="valorfora" class="form-control" placeholder="Insira o valor para o vencedor do time de fora" aria-describedby="sizing-addon2">
                            @if($errors->has('valorfora'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valorfora') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('valorgoal') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valorgoal">Valor Gol</label>
                            <input type="text" name="valorgoal" class="form-control" placeholder="Insira o valor para o vencedor de mais de um goal" aria-describedby="sizing-addon2">
                            @if($errors->has('valorgoal'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valorgoal') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('valordupla') ? 'has-error' : ''}}">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valordupla">Valor Dupla</label>
                            <input type="text" name="valordupla" class="form-control" placeholder="Insira o valor para o vencedor do time de fora ou empate" aria-describedby="sizing-addon2">
                            @if($errors->has('valordupla'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valordupla') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="campeonato_id">Descrição do Campeonato</label>
                            <select id="campeonato_id"  name="campeonato_id" class="form-control">
                                <option valeu="null">Selecione</option>
                                    @foreach (App\Campeonato::all() as $campeonato)
                                    <option valeu="{{ $campeonato->id }}"> {{ $campeonato->id }}</option>
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
