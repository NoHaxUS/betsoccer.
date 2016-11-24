@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row" style="margin-top:60px">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <fieldset>
                  <div class="form-group">
                    <h2>Faça Login</h2>
                    <hr class="colorgraph">
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" id="email" class="form-control input-lg" value="{{ old('email') }}" placeholder="Email Address"/>
                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif

                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                    <input id="password" type="password" class="form-control" name="password" placeholder="Password">

                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif

                </div>


                <div class="form-group">
                   <span class="button-checkbox">
                    <button type="button" class="btn" data-color="info">Lembrar</button>
                    <input type="checkbox" name="remember_me" id="remember_me" checked="checked" class="hidden"/>
                    <a class="btn btn-link pull-right" href="{{ route('senha.recu') }}">Esqueceu sua Senha?</a>
                </span>
                <hr class="colorgraph">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Entrar"/>
                    </div>

                </div>

            </div>

        </fieldset>
    </form>
</div>
</div>

</div>  
@endsection
