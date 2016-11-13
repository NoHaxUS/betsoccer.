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
                        
                        <div class="form-group {{ $errors->has('data') ? 'has-error' : ''}}">
                            <label for="data">Horário</label>
                            <div class='input-group date form-group' id='datetimepicker1' name="data">
                                <input type='text' class="form-control" name="data"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            @if($errors->has('data'))                                
                            <span class="help-block">
                                <strong> {{ $errors->first('data') }} </strong>
                            </span>
                            @endif 
                        </div>
                        <div class="form-group {{ $errors->has('time_id') ? 'has-error' : ''}}">
                            <label for="time_id">Time Casa</label>
                            <select id="time_id"  name="time_id" class="form-control">
                                <option value="">Selecione</option>
                                @foreach ($times as $time)
                                <option value="{{ $time->id }}">{{ $time->descricao_time }}</option>
                                @endforeach     
                            </select>
                            @if($errors->has('time_id'))                                
                            <span class="help-block">
                                <strong> {{ $errors->first('time_id') }} </strong>
                            </span>
                            @endif 
                        </div>
                        <div class="form-group {{ $errors->has('timef_id') ? 'has-error' : ''}}">
                            <label for="timef_id">Time Fora</label>
                            <select id="timef_id"  name="timef_id" class="form-control">
                                <option value="">Selecione</option>
                                @foreach ($times as $time)
                                <option value="{{ $time->id }}">{{ $time->descricao_time }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('timef_id'))
                            <span class="help-block">
                                <strong> {{ $errors->first('timef_id') }} </strong>
                            </span>
                            @endif    
                        </div>


                        <!-- # Select dropdown de valores #-->
                        <div class="input-group {{ $errors->has('valor_casa') ? 'has-error' : ''}}">
                            <label for="valor_casa">Valor Casa</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>


                        <div class="input-group {{ $errors->has('valor_emp') ? 'has-error' : ''}}">
                            <label for="valor_emp">Valor Empate</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>

                        <div class="input-group {{ $errors->has('valor_fora') ? 'has-error' : ''}}">
                            <label for="valor_fora">Valor Fora</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>


                        <div class="input-group {{ $errors->has('valor_fora') ? 'has-error' : ''}}">
                            <label for="valor_gol">Valor Gol</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>


                        <div class="input-group {{ $errors->has('valor_fora') ? 'has-error' : ''}}">
                            <label for="valor_dupla">Valor Dupla</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>

                        <div class="input-group {{ $errors->has('valor_menos_dois_cinco') ? 'has-error' : ''}}">
                            <label for="valor_menos_dois_cinco">Valor -2.5</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>

                        <div class="input-group {{ $errors->has('valor_mais_dois_cinco') ? 'has-error' : ''}}">
                            <label for="valor_mais_dois_cinco">Valor -2.5</label>
                            <select id="valor_id"  name="valor_id" class="form-control">
                                <option value="">Selecione Valor</option>
                                @foreach ($valores as $valor)
                                    <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('valor_id'))
                                <span class="help-block">
                                <strong> {{ $errors->first('valor_id') }} </strong>
                            </span>
                            @endif
                        </div>

                        <!-- ########## fim select drop valores #############-->


                        <label for="ambas_gol">Valor Ambas</label>
                        <div class="input-group {{ $errors->has('ambas_gol') ? 'has-error' : ''}}">
                            <span class="input-group-addon" id="sizing-addon2">$</span>
                            
                            <input type="text" name="ambas_gol" class="form-control" placeholder="Insira o valor para se a duas equipes marcarem gol" aria-describedby="sizing-addon2">
                            @if($errors->has('ambas_gol'))
                            <span class="help-block">
                                <strong> {{ $errors->first('ambas_gol') }} </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('campeonatos_id') ? 'has-error' : ''}}">
                            <label for="campeonatos_id">Descrição do Campeonato</label>
                            <select id="campeonatos_id"  name="campeonatos_id" class="form-control">
                                <option value="">Selecione</option>
                                @foreach ($campeonatos as $campeonato)
                                <option value="{{ $campeonato->id }}">{{ $campeonato->descricao_campeonato }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('campeonatos_id'))
                            <span class="help-block">
                                <strong> {{ $errors->first('campeonatos_id') }} </strong>
                            </span>
                            @endif
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