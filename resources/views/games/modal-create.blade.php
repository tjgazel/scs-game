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
        <div class="container-fluid">
          <form method="POST" action="{{ route('games.store') }}">
            <div class="row">
              <div class="col">
                @csrf
                <div class="form-group">
                  <label for="name" class="font-weight-bold">Nome da cadeia de suprimentos</label>
                  <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                         name="name" value="{{ old('name') }}" autofocus>
                  @if ($errors->has('name'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group mb-0">
                  <label class="font-weight-bold">Tempo do jogo<br/> </label>
                </div>
                <div class="custom-control custom-radio">
                  <input type="radio" id="customRadio1" name="max_weeks" value="26" class="custom-control-input">
                  <label class="custom-control-label" for="customRadio1">Rápido <small>(26 turnos)</small></label>
                </div>
                <div class="custom-control custom-radio">
                  <input type="radio" id="customRadio2" checked name="max_weeks" value="52" class="custom-control-input">
                  <label class="custom-control-label" for="customRadio2">Normal <small>(52 turnos)</small></label>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="max_wait" class="font-weight-bold">Tempo máximo de espera por turno</label>
                  <input id="max_wait" type="number" class="form-control-sm form-control{{ $errors->has('max_wait') ? ' is-invalid' : '' }}"
                         name="max_wait" value="5" autofocus>
                  @if ($errors->has('max_wait'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('max_wait') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="cost_stock" class="font-weight-bold">Estoque:<br/> Custo unitário</label>
                  <input id="cost_stock" type="number" class="form-control-sm form-control{{ $errors->has('cost_stock') ? ' is-invalid' : '' }}"
                         name="cost_stock" value="15.00" autofocus>
                  @if ($errors->has('cost_stock'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('cost_stock') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="cost_delay" class="font-weight-bold">Pedidos em atraso:<br/> Custo unitário</label>
                  <input id="cost_delay" type="number" class="form-control-sm form-control{{ $errors->has('cost_delay') ? ' is-invalid' : '' }}"
                         name="cost_delay" value="35.00" autofocus>
                  @if ($errors->has('cost_delay'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('cost_delay') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group mb-0 mt-5">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fa fa-ban"></i> Cancelar
                  </button>
                  <button type="submit" class="btn btn-dark btn-sm">
                    <i class="fa fa-play"></i> Iniciar
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>