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
                        <div class='input-group date form-group' id='datetimepicker1' name="data">
                            <input type='text' class="form-control" name="data"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
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
