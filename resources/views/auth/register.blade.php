@extends('layouts.base')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><i class="fa fa-edit"></i> Novo Cadastro</div>
        
        <div class="card-body">
          <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
              <label for="name">Apelido</label>
              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                     name="name" value="{{ old('name') }}" required autofocus>
              
              @if ($errors->has('name'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="email">E-mail</label>
              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                     name="email" value="{{ old('email') }}" required>
              
              @if ($errors->has('email'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="password">Senha</label>
              <input id="password" type="password"
                     class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
              
              @if ($errors->has('password'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="password-confirm">Confirmar senha</label>
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
            
            <div class="form-group mb-0">
              <button type="submit" class="btn btn-dark btn-block">
                <i class="fa fa-save"></i> Salvar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
