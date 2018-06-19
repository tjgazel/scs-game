<template>
  <div class="modal fade" id="gameList" tabindex="-1" role="dialog" aria-labelledby="gameListTitle"
       aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="gameListTitle">
            <i class="fa fa-sign-in-alt"></i> Entrar em um jogo existente <br><br>
            <input v-model="filter" class="form-control form-control-sm" placeholder="Buscar jogo">
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="overflow-y: auto; height: 320px">
          <h5 v-for="game in games" class="border-bottom py-2">
            <a :href="link(game.id)"><i class="fa fa-play"></i> {{ game.name }}</a>
            <small class="float-right">
              <small>
                <i class="far fa-calendar"></i> {{ date(game.updated_at) }}
              </small>&nbsp;&nbsp;
              <small v-if="game.status == 1" class="text-success">
                <i class="far fa-flag"></i> Ativo
              </small>
              <small v-if="game.status == 0" class="text-danger">
                <i class="fa fa-ban"></i> Finalizado
              </small>
            </small>
          </h5>
          <!--@endforeach-->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fa fa-ban"></i> Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['data'],
    data() {
      return {
        filter: ''
      }
    },
    computed: {
      games() {
        return _.filter(JSON.parse(this.data), item => {
          return item.name.indexOf(this.filter) >= 0;
        });
      }
    },
    methods: {
      link(gameId) {
        return `${window.appUrl}/games/${gameId}`;
      },
      date(dt) {
        return window.moment(new Date(dt)).format('DD/MM/YY HH:mm');
      }
    }
  }
</script>
