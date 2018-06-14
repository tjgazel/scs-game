@extends('layouts.base')
@section('title', 'Iniciar ou entrar no jogo - ')
@section('content')
  <div class="row mb-5">
    <div class="col text-center">
      <img class="img-fluid" src="{{ asset('images/scheme.svg') }}" alt="">
    </div>
  </div>
  <div class="row">
    <div class="col text-center">
      <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#newGameForm">
        <i class="fa fa-plus"></i> Iniciar novo jogo
      </button>
      <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#gameList">
        <i class="fa fa-sign-in-alt"></i> Entrar em um jogo existente
      </button>
    </div>
  </div>
  
  <!-- Modal Create -->
  @include('games.modal-create')
  
  {{-- Modal List --}}
  <game-list data="{{ $games }}"></game-list>
@endsection()
