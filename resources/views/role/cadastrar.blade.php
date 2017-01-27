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
                        <form action="{{ route('role.salvar')}}" method="post">
                            {{ csrf_field() }}
                            @if($errors->any())
                                <ul class="alert alert-warning">
                                    @foreach(collect($errors->all())->unique() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="form-group">

                                {!! Form::label ('name', 'Name: ') !!}
                                {!! Form::text ('name', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label ('display_name', 'Display_name: ') !!}
                                {!! Form::text ('display_name', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label ('description', 'Description: ') !!}
                                {!! Form::text ('description', null, ['class'=>'form-control']) !!}
                            </div>
                            @permission('relacionar-permission')
                            <div class="form-group">
                                <fieldset>
                                    <ul id="roles">
                                        <legend>Permissions</legend>
                                        @foreach($permissions as $permission)
                                            {!! Form::checkbox('permissions[]', $permission->id, null,['id'=>$permission->id, 'class'=>'filled-in']) !!}
                                            {!! Form::label($permission->id, $permission->name) !!}
                                            <br/>
                                        @endforeach
                                    </ul>
                                </fieldset>
                            </div>
                            @endpermission
                            <button class="btn btn-info">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
