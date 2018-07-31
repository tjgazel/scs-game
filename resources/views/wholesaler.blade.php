@extends('layouts.base-vue')
@section('title', 'Atacadista em ' . $wholesaler->game->name . ' - ')
@section('content')
  <menu-in-game
    stakeholder="wholesaler"
    data-json="{{$wholesaler}}"
    game-out-url="{{route('wholesaler.gameout', ['id' => $wholesaler->id])}}">
  </menu-in-game>
  
  <wholesaler
    game-id="{{$wholesaler->game->id}}"
    submit-url="{{route('wholesaler.your-order', ['gameId' => $wholesaler->game->id])}}"
    next-week-url="{{route('wholesaler.next-week', ['gameId' => $wholesaler->game->id])}}"
    data-url="{{route('wholesaler.stakeholder', ['gameId' => $wholesaler->game->id])}}"
    game-off-url="{{route('games.show', ['gameId' => $wholesaler->game->id])}}"
    game-out-url="{{route('wholesaler.gameout', ['id' => $wholesaler->id])}}">
  </wholesaler>
  
  <week-logs
    game-id="{{$wholesaler->game->id}}"
    data-url="{{route('wholesaler.weeklog', ['gameId' => $wholesaler->game->id])}}">
  </week-logs>
@endsection