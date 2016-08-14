@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('horario.index') }}">Horarios</a></li>
                    <li class="active">Cadastrar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('horario.salvar')}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('data') ? 'has-error' : ''}} ">
                            <label for="data">Descricao do Time</label>
                            <input type="date" name="data" class="form-control" placeholder="Insira um horario para ser cadastrado">
                             
                           @if($errors->has('data'))
                            <span class="help-block">
                                <strong> {{ $errors->first('data') }} </strong>
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
