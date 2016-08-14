@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('horario.index') }}">Times</a></li>
                    <li class="active">Editar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('horario.atualizar',$horario->id)}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group">
                            <label for="descricao_time">Descricao</label>
                            <input type="date" name="date" class="form-control" placeholder="Troque a data" value="{{$horario->data}}">
                        </div>
                        <button class="btn btn-info">Editar</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
