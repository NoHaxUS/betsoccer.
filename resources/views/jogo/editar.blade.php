@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('jogo.index') }}">Jogos</a></li>
                    <li class="active">Editar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('jogo.atualizar',$jogo->id)}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group">
                            <label for="horario">Horário</label>
                            <input type="text" name="horario" class="form-control" value="{{ $jogo->horario}}">
                        </div>
                        <div class="form-group">
                            <label for="timecasa">Time Casa</label>
                            <input type="text" name="timecasa" class="form-control" value="{{ $jogo->timecasa}}">
                         </div>
                         <div class="form-group">   
                            <label for="timefora">Time Fora</label>
                            <input type="text" name="timefora" class="form-control" value="{{ $jogo->timefora}}">
                        </div>
                        <div class="input-group">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valorcasa">Valor Casa</label>
                            <input type="text" name="valorcasa" class="form-control" value="{{ $jogo->valorcasa }}" aria-describedby="sizing-addon2">

                       </div>
                       
                        <div class="input-group">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                        	<label for="valoremp">Valor Emp</label>
                            <input type="text" name="valoremp" class="form-control" value="{{ $jogo->valoremp }}" aria-describedby="sizing-addon2">
                        </div>

                        <div class="input-group">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valorfora">Valor Fora</label>
                            <input type="text" name="valorfora" class="form-control" value="{{ $jogo->valorfora }}" aria-describedby="sizing-addon2">
                        </div>
                        <div class="input-group">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valorgoal">Valor Gol</label>
                            <input type="text" name="valorgoal" class="form-control" value="{{ $jogo->valorgoal }}" aria-describedby="sizing-addon2">
                        </div>
                        <div class="input-group">
                        	<span class="input-group-addon" id="sizing-addon2">$</span>
                            <label for="valordupla">Valor Dupla</label>
                            <input type="text" name="valordupla" class="form-control" value="{{ $jogo->valordupla }}" aria-describedby="sizing-addon2">
                        </div>
                        <div class="form-group">
                        	<label for="campeonato_id">Descrição do Campeonato</label>
                            <input type="text" name="campeonato_id" class="form-control" value="{{ $jogo->campeonato_id }}">
                        </div>
                        <button class="btn btn-info">Editar</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
