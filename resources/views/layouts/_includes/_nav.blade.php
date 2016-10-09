 <nav class="navbar navbar-default navbar-static-top ">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                BestSoccer
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->

            @if (!Auth::guest())
            @can('show', Auth::user())
            <ul class="nav navbar-nav">                
                <li><a href="{{ route('time.index') }}">Times</a></li>            
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{ route('campeonato.index') }}">Campeonatos</a></li>               
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{ route('horario.index') }}">horario</a></li>            
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{ route('jogo.index') }}">Jogos</a></li>            
            </ul>
             @endcan
            <ul class="nav navbar-nav">
                <li><a href="{{ route('aposta.index') }}">Aposta</a></li>
            </ul>
            @endif
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Entrar</a></li>                
                @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>                    
                    <ul class="dropdown-menu" role="menu">
                        @can('show', Auth::user())
                        <li><a href="{{ route('reg.get') }}">Registrar</a></li>
                        @endcan
                        <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Sair</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>