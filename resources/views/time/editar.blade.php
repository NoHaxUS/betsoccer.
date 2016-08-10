@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('time.index') }}">Times</a></li>
                    <li class="active">Editar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('time.atualizar',$time->id)}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group">
                            <label for="descricao_time">Descricao</label>
                            <input type="text" name="descricao_time" class="form-control" placeholder="Insira o nome do time que será cadastrado" value="{{$time->descricao_time}}">
                        </div>
                        <button class="btn btn-info">Editar</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
