@extends('layouts.base-vue')
@section('title', 'Distribuidor em ' . $distributor->game->name . ' - ')
@section('content')
  <menu-in-game
    stakeholder="distributor"
    data-json="{{$distributor}}"
    game-out-url="{{route('distributor.gameout', ['id' => $distributor->id])}}">
  </menu-in-game>
  
  <distributor
    game-id="{{$distributor->game->id}}"
    submit-url="{{route('distributor.your-order', ['gameId' => $distributor->game->id])}}"
    next-week-url="{{route('distributor.next-week', ['gameId' => $distributor->game->id])}}"
    data-url="{{route('distributor.stakeholder', ['gameId' => $distributor->game->id])}}"
    game-off-url="{{route('games.show', ['gameId' => $distributor->game->id])}}">
  </distributor>
  
  <week-logs
    game-id="{{$distributor->game->id}}"
    data-url="{{route('distributor.weeklog', ['gameId' => $distributor->game->id])}}">
  </week-logs>
@endsection