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
                        {!! Form::open(['route'=>['user.atualizar', $user->id], 'method'=>'put']) !!}
                        {{ csrf_field() }}
                        @if($errors->any())
                            <ul class="alert alert-warning">
                                @foreach(collect($errors->all())->unique() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="form-group">
                            {!! Form::hidden ('id', $user->id, ['class'=>'form-control']) !!}
                        </div>
                        @permission('editar-user')
                        <div class="form-group">
                            {!! Form::label ('name', 'Name: ') !!}
                            {!! Form::text ('name', $user->name, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('email', 'E-mail: ') !!}
                            {!! Form::text ('email', $user->email, ['class'=>'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label ('codigo_seguranca', 'Codigo de seguranca: ') !!}
                            {!! Form::text('codigo_seguranca',$user->codigo_seguranca, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('password', 'Senha: ') !!}
                            {!! Form::password ('password', ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('password_confirmation', 'Confirmar a senha: ') !!}
                            {!! Form::password ('password_confirmation', ['class'=>'form-control']) !!}
                        </div>
                        @else
                            <div class="form-group">
                                {!! Form::label ('name', 'Name: ') !!}
                                {!! Form::text ('name', $user->name, ['class'=>'form-control','readonly']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label ('email', 'E-mail: ') !!}
                                {!! Form::text ('email', $user->email, ['class'=>'form-control','readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label ('codigo_seguranca', 'Codigo de seguranca: ') !!}
                                {!! Form::text('codigo_seguranca',$user->codigo_seguranca, ['class'=>'form-control','readonly']) !!}
                            </div>
                            @endpermission

                            <div class="form-group">
                                <fieldset>
                                    <ul id="roles">
                                        <legend>Roles</legend>
                                        @if($roles!=null || !$roles->isEmpty)
                                            @permission('relacionar-role')
                                            @foreach($roles as $role)
                                                {!! Form::checkbox('roles[]', $role->id, $user->roles->contains($role),['id'=>$role->id, 'class'=>'filled-in']) !!}
                                                {!! Form::label($role->id, $role->name) !!}
                                                <br/>
                                            @endforeach
                                        @else
                                            @foreach($user->roles as $role)
                                                {!! Form::hidden ('roles[]', $role->id) !!}
                                                {!! Form::label($role->id, $role->name) !!}
                                                <br/>
                                            @endforeach
                                            @endpermission
                                        @endif
                                    </ul>
                                </fieldset>
                            </div>

                            <button class="btn btn-info">Cadastrar</button>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
