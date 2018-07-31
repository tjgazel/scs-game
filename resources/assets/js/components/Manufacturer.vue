<template>
  <div class="container-fluid">
    <div class="row" v-show="loading">
      <div class="col-12 mx-auto">
        <div class="progress" style="height: 30px;">
          <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
               aria-valuenow="100"
               aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            <b>Não atualize o navegador - Sincronizando jogadores.</b>
          </div>
        </div>
      </div>
    </div>

    <div class="row pt-3">

      <div class="col-12 col-md-3 pr-md-0 mb-3">
        <div class="card">
          <div class="card-header">
            <i class="fas fa-users"></i> Pedidos de clientes
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <i class="fas fa-shopping-cart"></i> Novos pedidos
              <span class="badge badge-success badge-size float-right">{{data.weekLog.new_order}}</span><br>
              <small class="text-muted">Novos pedidos de clientes nesta semana.</small>
            </li>
            <li class="list-group-item">
              <i class="fas fa-user-clock"></i> Pedidos em atraso
              <span class="badge badge-danger badge-size float-right">{{data.lastBackOrder}}</span><br>
              <small class="text-muted">Todos os pedidos que você não pode atender nas semanas anteriores.</small>
            </li>
            <li class="list-group-item">
              <i class="fas fa-truck-loading"></i> Para entregar
              <span class="badge badge-secondary badge-size float-right">{{data.weekLog.to_ship}}</span> <br>
              <small class="text-muted">Novos pedidos + pedidos em atraso.</small>
            </li>
          </ul>
        </div>
      </div>

      <div class="col-12 order-first col-md-6 order-md-0 mb-3">
        <div class="card">
          <div class="card-header">
            <i class="fas fa-flag"></i> {{data.week}}/{{data.maxWeeks}} - Situação atual
          </div>
          <div class="card-body pb-2">
            <div class="row">
              <div class="col card mx-1 p-0">
                <div class="card-body text-center">
                  <i class="fas fa-cubes icon-size"></i> <br>
                  Estoque atualizado<br><br>
                  <span class="badge badge-secondary badge-size">{{data.weekLog.inventory}}</span>
                </div>
              </div>
              <div class="col card mx-1 p-0">
                <div class="card-body text-center">
                  <i class="fas fa-people-carry icon-size"></i> <br>
                  Produção da semana <br><br>
                  <span class="badge badge-success badge-size">{{data.weekLog.incoming}}</span>
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col card mx-1 p-0">
                <div class="card-body text-center">
                  <i class="fas fa-truck icon-size"></i> <br>
                  Enviado aos clientes <br><br>
                  <span class="badge badge-success badge-size">{{data.weekLog.delivery}}</span>
                </div>
              </div>
              <div class="col card mx-1 p-0">
                <div class="card-body text-center">
                  <i class="fas fa-user-clock icon-size"></i> <br>
                  Novos pedidos em atraso <br><br>
                  <span class="badge badge-danger badge-size">{{data.weekLog.back_order}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-3 pl-md-0 mb-3">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-cogs"></i> Processo de produção
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" v-model="inputYourOrder" @keyup="inputChange($event)"
                       :disabled="loading">
                <div class="input-group-append">
                  <button @click="onSubmit" class="btn btn-dark btn-sm" type="button"
                          :disabled="loading">
                    <i class="fa fa-angle-double-right"></i> Fazer Pedido
                  </button>
                </div>
              </div>
              <small v-if="inputError" class="text-danger">
                <i class="fas fa-exclamation-triangle"></i> Digite um valor válido ou 0.<br>
              </small>
              <small class="text-muted">
                Digite a quantidade de produtos que queira colocar em produção e clique no botão para prosseguir a
                semana.
              </small>
            </li>
            <li class="list-group-item">
              <i class="fas fa-users-cog"></i> 1ª etapa de produção
              <span class="badge badge-info badge-size float-right">{{data.incomingWeekOne}}</span> <br>
            </li>
            <li class="list-group-item">
              <i class="fas fa-users-cog"></i> 2ª etapa de produção
              <span class="badge badge-warning text-white badge-size float-right">{{data.incomingWeekTwo}}</span> <br>
            </li>
            <li class="list-group-item">
              <small class="text-muted">Seu processo de produção é feito em 2 etapas. Cada etapa leva 1 semana para ser
                concluída.
              </small>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
  import {randomInt, alertSubmit, alertSeason} from '../modules/functions';

  export default {
    props: ['gameId', 'dataUrl', 'submitUrl', 'nextWeekUrl', 'gameOffUrl', 'gameOutUrl'],
    mounted() {
      this.loadData();

      /* Mensagem de submit */
      Echo.channel(`DistributorYourOrderEvent.${this.gameId}`)
        .listen('DistributorYourOrderEvent', (e) => {
          this.orderEvent = true;
          alertSubmit('Distribuidor');
        });
      Echo.channel(`WholesalerYourOrderEvent.${this.gameId}`)
        .listen('WholesalerYourOrderEvent', (e) => {
          this.orderEvent = true;
          alertSubmit('Atacadista');
        });
      Echo.channel(`RetailerYourOrderEvent.${this.gameId}`)
        .listen('RetailerYourOrderEvent', (e) => {
          this.orderEvent = true;
          alertSubmit('Varejista');
        });

      /* Finalizando ciclo */
      Echo.channel(`RetailerWeekEvent.${this.gameId}`)
        .listen('RetailerWeekEvent', (e) => {
          if (e.gameOff) {
            window.location = this.gameOffUrl;
          }
          window.location.reload();
        });
      Echo.channel(`ManufacturerInactivePlayer.${this.gameId}`)
        .listen('ManufacturerInactivePlayer', (e) => {
          window.location = `${window.appUrl}/games/${this.gameId}`;
        });
    },
    data() {
      return {
        inputYourOrder: 0,
        inputError: false,
        data: {
          week: 0,
          maxWeeks: 0,
          weekLog: {},
          lastBackOrder: 0,
          incomingWeekOne: 0,
          incomingWeekTwo: 0
        },
        loading: false,
        orderEvent: false
      }
    },
    methods: {
      loadData() {
        axios.get(this.dataUrl)
          .then(res => {
            this.data = res.data;
            const wait = (res.data.maxWait * 60000) - 15000;
            setTimeout(this.timeOut, wait);
            alertSeason(res.data.maxWeeks, res.data.week);
          })
          .catch(error => console.log(error));
      },
      nextWeek() {
        axios.post(this.nextWeekUrl, {}).catch(error => console.log(error));
      },
      onSubmit() {
        this.loading = true;
        axios.post(this.submitUrl, {your_order: this.inputYourOrder}).catch(error => console.error(error));
      },
      inputChange: _.debounce(function (e) {
        if (isNaN(e.target.value)) {
          this.inputError = true;
          this.inputYourOrder = 0;
        } else if (parseInt(e.target.value)) {
          this.inputError = false;
          this.inputYourOrder = parseInt(e.target.value);
        } else {
          this.inputError = false;
          this.inputYourOrder = 0;
        }
      }, 500),
      timeOut() {
        if (this.orderEvent) {
          axios.post(this.submitUrl, {your_order: randomInt(35, 60)}).then(res => {
            window.location = `${window.appUrl}/games/${this.gameId}`;
          }).catch(error => console.error(error));
        } else {
          window.location = `${window.appUrl}/games/${this.gameId}`;
        }
      }
    }
  };
</script>

<style scoped>
  .badge-size {
    font-size: 1.2rem;
  }

  .icon-size {
    font-size: 2.5rem;
  }
</style>