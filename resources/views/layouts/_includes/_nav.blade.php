        <nav class="navbar navbar-fixed-top">
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
                <ul class="nav navbar-nav ">
                    <li class="dropdown">
                       <a href="#" class="dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                        Time <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('time.cadastrar') }}">Cadastrar</a></li>
                        <li><a href="{{ route('time.index') }}">Listar</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Campeonato <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('campeonato.cadastrar') }}">Cadastrar</a></li>
                    <li><a href="{{ route('campeonato.index') }}">Listar</a></li>
                </ul>
            </li>

            <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Jogos <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('jogo.cadastrar') }}">Cadastrar</a></li>
                <li><a href="{{ route('jogo.index') }}">Listar</a></li>
            </ul>
        </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Telefones <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('telefone.cadastrar') }}">Cadastrar</a></li>
                            <li><a href="{{ route('telefone.index') }}">Listar</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Dispositivos <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('dispositivo.cadastrar') }}">Cadastrar</a></li>
                            <li><a href="{{ route('dispositivo.index') }}">Listar</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('jogo.allJogosPlacar') }}">Placar</a></li>

        <li><a href="{{ route('aposta.listaAposta') }}">ApostasVencedoras</a></li>

        <li><a href="{{ route('apostaJogo.resumoAposta') }}">Relatorios</a></li>
        @endcan

        <li><a href="{{ route('aposta.index') }}">Apostar</a></li>
        @endif
    </ul>
    <!-- Right Side Of Navbar -->
    <ul class="nav navbar-nav navbar-right">
        <!-- Authentication Links -->
        @if (Auth::guest())
        <li><a href="{{ url('/login') }}">Entrar</a></li>
        @else
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @can('show', Auth::user())
                <li><a href="{{ route('reg.get') }}">Registrar</a></li>
                <li><a href="{{ route('user.edit') }}">Editar Cambista</a></li>
                @endcan
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Usuarios <span class="caret"></span>
                    </a>
                    <ul class="dropdown">
                        <li><a href="{{ route('user.cadastrar') }}">Cadastrar</a></li>
                        <li><a href="{{ route('user.index') }}">Listar</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Roles <span class="caret"></span>
                    </a>
                    <ul class="dropdown">
                        <li><a href="{{ route('role.cadastrar') }}">Cadastrar</a></li>
                        <li><a href="{{ route('role.index') }}">Listar</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Permissions <span class="caret"></span>
                    </a>
                    <ul class="dropdown">
                        <li><a href="{{ route('permission.cadastrar') }}">Cadastrar</a></li>
                        <li><a href="{{ route('permission.index') }}">Listar</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Sair</a></li>
            </ul>
        </li>
        @endif
    </ul>
</div>
</div>
</nav>