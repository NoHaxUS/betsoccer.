@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('campeonato.index') }}">Campeonato</a></li>
                    <li class="active">Cadastrar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('campeonato.salvar')}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('descricao_campeonato') ? 'has-error' : ''}} ">
                            <label for="descricao_campeonato">Descricao do campeonato</label>
                            <input type="text" name="descricao_campeonato" class="form-control" placeholder="Insira o nome do campeonato que será cadastrado">
                            @if($errors->has('descricao_campeonato'))
                            <span class="help-block">
                                <strong> {{ $errors->first('descricao_campeonato') }} </strong>
                            </span>
                            @endif
                        </div>
                        <button class="btn btn-info">Cadastrar</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
