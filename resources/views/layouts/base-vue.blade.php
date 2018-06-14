<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="app-url" content="{{ config('app.url') }}">

  <title>@yield('title'){{ config('app.name'), 'SCS Game' }}</title>

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
  <div id="app">
    @yield('content')
  </div>
  <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
  {!! toastr()->render() !!}
</body>
</html>