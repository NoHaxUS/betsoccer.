@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <ol class="breadcrumb panel-heading">
                        <li><a href="{{ route('telefone.index') }}">Telefone</a></li>
                        <li class="active">Cadastrar</li>
                    </ol>

                    <div class="panel-body">
                        {!! Form::open(['route'=>['telefone.atualizar', $telefone->id], 'method'=>'put']) !!}
                        {{ csrf_field() }}
                        @if($errors->any())
                            <ul class="alert alert-warning">
                                @foreach(collect($errors->all())->unique() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::hidden ('id', $telefone->id) !!}

                        {!! Form::label ('ddd', 'DDD: ') !!}
                        {!! Form::text ('ddd', $telefone->ddd,['maxlength'=>"2"]) !!}

                        {!! Form::label ('numero', 'Numero: ') !!}
                        {!! Form::text ('numero', $telefone->numero,['maxlength'=>"9"]) !!}

                        {!! Form::label ('user_id', 'Usuario: ') !!}
                        {!! Form::select('user_id',$users, $telefone->user_id) !!}

                        <button class="btn btn-info">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
