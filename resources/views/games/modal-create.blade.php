<!-- Modal Create -->
<div class="modal fade" id="newGameForm" tabindex="-1" role="dialog" aria-labelledby="newGameFormTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newGameFormTitle"><i class="fa fa-plus"></i> Iniciar novo jogo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('games.store') }}">
          @csrf
          <div class="form-group">
            <label for="name" class="font-weight-bold">Nome da cadeia de suprimentos</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' :
            '' }}"
                   name="name" value="{{ old('name') }}" autofocus>
            @if ($errors->has('name'))
              <span class="invalid-feedback">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group mb-0">
            <label class="font-weight-bold">Tempo do jogo</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio1" name="max_weeks" value="26" class="custom-control-input">
            <label class="custom-control-label" for="customRadio1">Rápido <small>(26 semanas)</small></label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio2" checked name="max_weeks" value="52" class="custom-control-input">
            <label class="custom-control-label" for="customRadio2">Normal <small>(52 semanas)</small></label>
          </div>
          <div class="form-group mb-0 mt-5">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
              <i class="fa fa-ban"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-dark btn-sm">
              <i class="fa fa-play"></i> Iniciar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>