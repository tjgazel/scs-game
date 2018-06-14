@extends('layouts.base')
@section('title', 'Meus dados - ' )
@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><i class="fa fa-user"></i> Meus dados</div>
        
        <div class="card-body">
          <form method="POST" action="{{ route('users.update', ['id' => $user->id]) }}">
            @csrf @method('put')
            
            <div class="form-group">
              <label for="name">Apelido</label>
              <input id="name" name="name" type="text"
                     class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                     value="@if(old('name')){{old('name')}}@else{{auth()->user()->name}}@endif" required>
              
              @if ($errors->has('name'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="email">E-mail</label>
              <input id="email" name="email" type="email"
                     class="form-control{{ $errors->has('email') ? 'is-invalid' : '' }}"
                     value="@if(old('email')){{old('email')}}@else{{auth()->user()->email}}@endif" required>
              
              @if ($errors->has('email'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="password">Alterar Senha <small class="text-info">(alteração opcional)</small></label>
              <input id="password" type="password"
                     class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
              
              @if ($errors->has('password'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="password-confirm">Confirmar nova senha</label>
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
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