@if(auth()->user()->retailer)
  <h5 class="dropdown-header">
    <i class="fas fa-play"></i> {{ auth()->user()->retailer->game->name }} <br><br>
    <i class="fas fa-shopping-cart"></i> Varejista
  </h5>
  <div class="dropdown-divider"></div>
  <a class="dropdown-item"
     href="{{ route('retailer.index', ['gameId' => auth()->user()->retailer->game->id]) }}">
    <i class="fas fa-share-square text-success"></i> Voltar ao jogo.
  </a>
  <a class="dropdown-item" href="{{ route('retailer.gameout', ['gameId' => auth()->user()->retailer->game->id]) }}"
     onclick="event.preventDefault(); document.getElementById('retailer-out-form').submit();">
    <i class="fas fa-ban text-danger"></i> Deixar a partida.
  </a>
  <form id="retailer-out-form" method="POST" style="display: none;"
        action="{{ route('retailer.gameout', ['gameId' => auth()->user()->retailer->game->id]) }}">
    @csrf
  </form>
  <div class="dropdown-divider"></div>
@elseif(auth()->user()->wholesaler)
  <h5 class="dropdown-header">
    <i class="fas fa-play"></i> {{ auth()->user()->wholesaler->game->name }} <br><br>
    <i class="fa fa-building"></i> Atacadista
  </h5>
  <div class="dropdown-divider"></div>
  <a class="dropdown-item"
     href="{{ route('wholesaler.index', ['gameId' => auth()->user()->wholesaler->game->id]) }}">
    <i class="fas fa-share-square text-success"></i> Voltar ao jogo.
  </a>
  <a class="dropdown-item" href="{{ route('wholesaler.gameout', ['gameId' => auth()->user()->wholesaler->game->id]) }}"
     onclick="event.preventDefault(); document.getElementById('wholesaler-out-form').submit();">
    <i class="fas fa-ban text-danger"></i> Deixar a partida.
  </a>
  <form id="wholesaler-out-form" method="POST" style="display: none;"
        action="{{ route('wholesaler.gameout', ['gameId' => auth()->user()->wholesaler->game->id]) }}">
    @csrf
  </form>
  <div class="dropdown-divider"></div>
@elseif(auth()->user()->distributor)
  <h5 class="dropdown-header">
    <i class="fas fa-play"></i> {{ auth()->user()->distributor->game->name }} <br><br>
    <i class="fa fa-truck"></i> Distribuidor
  </h5>
  <div class="dropdown-divider"></div>
  <a class="dropdown-item"
     href="{{ route('distributor.index', ['gameId' => auth()->user()->distributor->game->id]) }}">
    <i class="fas fa-share-square text-success"></i> Voltar ao jogo.
  </a>
  <a class="dropdown-item"
     href="{{ route('distributor.gameout', ['gameId' => auth()->user()->distributor->game->id]) }}"
     onclick="event.preventDefault(); document.getElementById('distributor-out-form').submit();">
    <i class="fas fa-ban text-danger"></i> Deixar a partida.
  </a>
  <form id="distributor-out-form" method="POST" style="display: none;"
        action="{{ route('distributor.gameout', ['gameId' => auth()->user()->distributor->game->id]) }}">
    @csrf
  </form>
  <div class="dropdown-divider"></div>
@elseif(auth()->user()->manufacturer)
  <h5 class="dropdown-header">
    <i class="fas fa-play"></i> {{ auth()->user()->manufacturer->game->name }} <br><br>
    <i class="fa fa-industry"></i> Fabricante
  </h5>
  <div class="dropdown-divider"></div>
  <a class="dropdown-item"
     href="{{ route('manufacturer.index', ['gameId' => auth()->user()->manufacturer->game->id]) }}">
    <i class="fas fa-share-square text-success"></i> Voltar ao jogo.
  </a>
  <a class="dropdown-item"
     href="{{ route('manufacturer.gameout', ['gameId' => auth()->user()->manufacturer->game->id]) }}"
     onclick="event.preventDefault(); document.getElementById('manufacturer-out-form').submit();">
    <i class="fas fa-ban text-danger"></i> Deixar a partida.
  </a>
  <form id="manufacturer-out-form" method="POST" style="display: none;"
        action="{{ route('manufacturer.gameout', ['gameId' => auth()->user()->manufacturer->game->id]) }}">
    @csrf
  </form>
  <div class="dropdown-divider"></div>
@endif