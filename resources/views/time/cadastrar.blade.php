@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('time.index') }}">Times</a></li>
                    <li class="active">Cadastrar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('time.salvar')}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('descricao_time') ? 'has-error' : ''}} ">
                            <label for="descricao_time">Descricao do Time</label>
                            <input type="text" name="descricao_time" class="form-control" placeholder="Insira o nome do time que será cadastrado">
                           @if($errors->has('descricao_time'))
                            <span class="help-block">
                                <strong> {{ $errors->first('descricao_time') }} </strong>
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
