@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <ol class="breadcrumb panel-heading">
                        <li><a href="{{ route('role.index') }}">Role</a></li>
                        <li class="active">Cadastrar</li>
                    </ol>

                    <div class="panel-body">
                        {!! Form::open(['route'=>['role.atualizar', $role->id], 'method'=>'put']) !!}
                        {{ csrf_field() }}
                        @if($errors->any())
                            <ul class="alert alert-warning">
                                @foreach(collect($errors->all())->unique() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="form-group">
                            {!! Form::hidden ('id', $role->id, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('name', 'Name: ') !!}
                            {!! Form::text ('name', $role->name, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('display_name', 'Display_name: ') !!}
                            {!! Form::text ('display_name', $role->display_name, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label ('description', 'Description: ') !!}
                            {!! Form::text ('description', $role->description, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <ul id="roles">
                                    <legend>Permissions</legend>


                                    @if($permissions!=null || !$permissions->isEmpty)
                                        @foreach($permissions as $permission)
                                            {!! Form::checkbox('permissions[]', $permission->id, $role->perms->contains($permission),['id'=>$permission->id, 'class'=>'filled-in']) !!}
                                            {!! Form::label($permission->id, $permission->name) !!}
                                            <br/>
                                        @endforeach
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
