@extends('layouts.base')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><i class="fa fa-sign-in-alt"></i> Entrar no jogo</div>
        
        <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
              <label for="email">E-mail</label>
              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                     name="email" value="{{ old('email') }}" required autofocus>
              
              @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="password" class="">Senha</label>
              <input id="password" type="password"
                     class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
              
              @if ($errors->has('password'))
                <span class="invalid-feedback">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif
            </div>
            
            {{--<div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembrar-me
                </label>
              </div>
            </div>--}}
            
            <div class="form-group mb-0">
              <button type="submit" class="btn btn-dark">
                <i class="fas fa-sign-in-alt"></i> Entrar
              </button>
              
              <a class="btn btn-link" href="{{ route('password.request') }}">
                Esqueceu sua senha?
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
