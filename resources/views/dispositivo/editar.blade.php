@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <ol class="breadcrumb panel-heading">
                        <li><a href="{{ route('dispositivo.index') }}">Dispositivo</a></li>
                        <li class="active">Cadastrar</li>
                    </ol>

                    <div class="panel-body">
                        {!! Form::open(['route'=>['dispositivo.atualizar', $dispositivo->id], 'method'=>'put']) !!}
                        {{ csrf_field() }}
                        @if($errors->any())
                            <ul class="alert alert-warning">
                                @foreach(collect($errors->all())->unique() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::hidden ('id', $dispositivo->id) !!}

                        {!! Form::label ('mac', 'MAC: ') !!}
                        {!! Form::text ('mac', $dispositivo->mac,['maxlength'=>'17']) !!}

                        {!! Form::label ('user_id', 'Usuário: ') !!}
                        {!! Form::select('user_id',$users, $dispositivo->user_id) !!}

                        <button class="btn btn-info">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
