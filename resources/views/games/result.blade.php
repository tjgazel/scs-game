@extends('layouts.base')
@section('title', 'Game: ' . $game->name . ' - ')
@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Resultados do jogo <br><small><i class="fa fa-play"></i> {{ $game->name }}</small></h1>
  </div>
</div>
<div class="row">

</div>
@endsection()