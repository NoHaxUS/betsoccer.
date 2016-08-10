@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('campeonato.index') }}">Campeonato</a></li>
                    <li class="active">Editar</li>
                </ol>
                <div class="panel-body">
                    <form action="{{ route('campeonato.atualizar',$campeonato->id)}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group">
                            <label for="descricao_campeonato">Descricao</label>
                            <input type="text" name="descricao_campeonato" class="form-control" value="{{$campeonato->descricao_campeonato}}">
                        </div>
                        <button class="btn btn-info">Editar</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
