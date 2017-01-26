@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('user.index') }}">Usuario</a></li>
                    <li class="active">Cadastrar</li>
                </ol>

                <div class="panel-body">
                    <form action="{{ route('user.salvar')}}" method="post">
                        {{ csrf_field() }}
                        @if($errors->any())
                            <ul class="alert alert-warning">
                                @foreach(collect($errors->all())->unique() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="form-group">
                            {!! Form::label ('name', 'Nome: ') !!}
                            {!! Form::text ('name', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('email', 'E-mail: ') !!}
                            {!! Form::text ('email', null, ['class'=>'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label ('codigo_seguranca', 'Codigo de seguranca: ') !!}
                            {!! Form::text ('codigo_seguranca',null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('password', 'Senha: ') !!}
                            {!! Form::password ('password', ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('password_confirmation', 'Confirmar a senha: ') !!}
                            {!! Form::password ('password_confirmation', ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <ul id="roles">
                                    <legend>Roles</legend>
                                    @foreach($roles as $role)
                                        {!! Form::checkbox('roles[]', $role->id, null,['id'=>$role->id, 'class'=>'filled-in']) !!}
                                        {!! Form::label($role->id, $role->name) !!}
                                        <br/>
                                    @endforeach
                                </ul>
                            </fieldset>
                        </div>

                        <button class="btn btn-info">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
