@extends('layouts.base-vue')
@section('title', 'Fabricante em ' . $manufacturer->game->name . ' - ')
@section('content')
  <menu-in-game
    stakeholder="manufacturer"
    data-json="{{$manufacturer}}"
    game-out-url="{{route('manufacturer.gameout', ['id' => $manufacturer->id])}}">
  </menu-in-game>
  
  <manufacturer
    game-id="{{$manufacturer->game->id}}"
    submit-url="{{route('manufacturer.your-order', ['gameId' => $manufacturer->game->id])}}"
    next-week-url="{{route('manufacturer.next-week', ['gameId' => $manufacturer->game->id])}}"
    data-url="{{route('manufacturer.stakeholder', ['gameId' => $manufacturer->game->id])}}"
    game-off-url="{{route('games.show', ['gameId' => $manufacturer->game->id])}}"
    game-out-url="{{route('manufacturer.gameout', ['id' => $manufacturer->id])}}">
  </manufacturer>
  
  <week-logs
    game-id="{{$manufacturer->game->id}}"
    data-url="{{route('manufacturer.weeklog', ['gameId' => $manufacturer->game->id])}}">
  </week-logs>
@endsection