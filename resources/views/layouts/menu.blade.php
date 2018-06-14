<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand mr-5" href="{{ url('/') }}">
      {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        @auth()
        <li><a class="nav-link" href="{{ route('games.index') }}"><i class="fas fa-play"></i> Jogar</a></li>
        @endauth
        <li><a class="nav-link" href="{{ route('game-instructions') }}"><i class="fas fa-info"></i> instruções do
            jogo</a></li>
      </ul>
      
      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto">
        @guest()
          <li><a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> Entrar no jogo</a></li>
          <li><a class="nav-link" href="{{ route('register') }}"><i class="fa fa-edit"></i> Cadastrar</a></li>
        @else
        <li class="nav-item dropdown">
          <a id="navbarUser" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
             aria-haspopup="true" aria-expanded="false" v-pre>
            <i class="fas fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
          </a>
          
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUser">
            @include('layouts.menu-in-game')
            <a class="dropdown-item" href="{{ route('users.edit', ['id' => auth()->user()->id]) }}">
              <i class="fas fa-user"></i> Meus dados.
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt text-danger"></i> Sair
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>