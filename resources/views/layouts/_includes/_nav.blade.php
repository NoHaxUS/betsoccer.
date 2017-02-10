<nav class="navbar navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
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
                <ul class="nav navbar-nav ">

                    @permission(['criar-time','consultar-time'])
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                            Time <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @permission('criar-time')
                            <li><a href="{{ route('time.cadastrar') }}">Cadastrar</a></li>
                            @endpermission
                            @permission('consultar-time')
                            <li><a href="{{ route('time.index') }}">Listar</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['criar-campeonato','consultar-campeonato'])
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Campeonato <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @permission('criar-campeonato')
                            <li><a href="{{ route('campeonato.cadastrar') }}">Cadastrar</a></li>
                            @endpermission
                            @permission('consultar-campeonato')
                            <li><a href="{{ route('campeonato.index') }}">Listar</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['criar-jogo','consultar-jogo'])
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Jogos <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @permission('criar-jogo')
                            <li><a href="{{ route('jogo.cadastrar') }}">Cadastrar</a></li>
                            @endpermission
                            @permission('consultar-jogo')
                            <li><a href="{{ route('jogo.index') }}">Listar</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['criar-telefone','consultar-telefone'])
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Telefones <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @permission('criar-telefone')
                            <li><a href="{{ route('telefone.cadastrar') }}">Cadastrar</a></li>
                            @endpermission
                            @permission('consultar-telefone')
                            <li><a href="{{ route('telefone.index') }}">Listar</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['criar-dispositivo','consultar-dispositivo'])
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Dispositivos <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @permission('criar-dispositivo')
                            <li><a href="{{ route('dispositivo.cadastrar') }}">Cadastrar</a></li>
                            @endpermission
                            @permission('consultar-dispositivo')
                            <li><a href="{{ route('dispositivo.index') }}">Listar</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission('editar-jogo')
                    <li><a href="{{ route('jogo.allJogosPlacar') }}">Placar</a></li>
                    @endpermission

                    @permission('consultar-aposta')
                    <li><a href="{{ route('aposta.listaAposta') }}">ApostasVencedoras</a></li>

                    <li><a href="{{ route('apostaJogo.resumoAposta') }}">Relatorios</a></li>
                    @endpermission

                    @permission('criar-aposta')
                    <li><a href="{{ route('aposta.index') }}">Apostar</a></li>
                    @endpermission
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
                                @permission(['ativar-user','desativar-user'])
                                <li><a href="{{ route('user.edit') }}">Editar Cambista</a></li>
                                @endpermission                            
                                @permission(['criar-user','consultar-user'])
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Usuarios <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown">
                                        @permission('criar-user')
                                        <li><a href="{{ route('user.cadastrar') }}">Cadastrar</a></li>
                                        @endpermission
                                        @permission('consultar-user')
                                        <li><a href="{{ route('user.index') }}">Listar</a></li>
                                        @endpermission
                                    </ul>
                                </li>
                                @endpermission
                                @permission(['criar-role','consultar-role'])
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Roles <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown">
                                        @permission('criar-role')
                                        <li><a href="{{ route('role.cadastrar') }}">Cadastrar</a></li>
                                        @endpermission
                                        @permission('consultar-role')
                                        <li><a href="{{ route('role.index') }}">Listar</a></li>
                                        @endpermission
                                    </ul>
                                </li>
                                @endpermission
                                @permission(['criar-permission','consultar-permission'])
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Permissions <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown">
                                        @permission('criar-permission')
                                        <li><a href="{{ route('permission.cadastrar') }}">Cadastrar</a></li>
                                        @endpermission
                                        @permission('consultar-permission')
                                        <li><a href="{{ route('permission.index') }}">Listar</a></li>
                                        @endpermission
                                    </ul>
                                </li>
                                @endpermission
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Sair</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
        </div>
    </div>
</nav>