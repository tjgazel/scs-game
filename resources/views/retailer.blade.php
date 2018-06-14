@extends('layouts.base-vue')
@section('title', 'Varejista em ' . $retailer->game->name . ' - ')
@section('content')
  <menu-in-game
    stakeholder="retailer"
    data-json="{{$retailer}}"
    game-out-url="{{route('retailer.gameout', ['id' => $retailer->id])}}">
  </menu-in-game>
  
  <retailer
    game-id="{{$retailer->game->id}}"
    submit-url="{{route('retailer.your-order', ['gameId' => $retailer->game->id])}}"
    next-week-url="{{route('retailer.next-week', ['gameId' => $retailer->game->id])}}"
    data-url="{{route('retailer.stakeholder', ['gameId' => $retailer->game->id])}}"
    game-off-url="{{route('games.show', ['gameId' => $retailer->game->id])}}">
  </retailer>
  
  <week-logs
    game-id="{{$retailer->game->id}}"
    data-url="{{route('retailer.weeklog', ['gameId' => $retailer->game->id])}}">
  </week-logs>
@endsection