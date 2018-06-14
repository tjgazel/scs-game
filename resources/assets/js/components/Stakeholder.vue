<template>
  <div class="container">
    <div class="row">

      <div class="col-12 col-md-3 p-md-0 mb-3">
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

      <div class="col-12 col-md-6 px-md-1 py-md-0 mb-3">
        <div class="card">
          <div class="card-header">
            <i class="fas fa-flag"></i> {{data.week}}/{{data.maxWeeks}}
            <small>Semana</small>
            <a href="#" class="float-right bg-info text-white px-2 rounded" @click.prevent="graphic">
              <i class="fas fa-chart-line"></i> Gráfico
            </a>
          </div>
          <div class="card-body pb-2">
            <div class="row">
              <div class="col card mx-1 p-0">
                <div class="card-body text-center">
                  <i class="fas fa-boxes icon-size"></i> <br>
                  Estoque atualizado<br><br>
                  <span class="badge badge-secondary badge-size">{{data.weekLog.inventory}}</span>
                </div>
              </div>
              <div class="col card mx-1 p-0">
                <div class="card-body text-center">
                  <i class="fas fa-people-carry icon-size"></i> <br>
                  <span v-if="stakeholder != 'manufacturer'">Entrada de estoque</span>
                  <span v-if="stakeholder == 'manufacturer'">Produção da semana</span> <br><br>
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

      <div class="col-12 col-md-3 p-md-0 mb-3">
        <div class="card">
          <div class="card-header">
            <i :class="{'fa fa-building': stakeholder == 'retailer',
                        'fa fa-dolly-flatbed': stakeholder == 'wholesaler',
                        'fa fa-industry': stakeholder == 'distributor',
                        'fa fa-cogs': stakeholder == 'manufacturer'}">
            </i> {{provider}}
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" v-model="inputYourOrder" @keyup="inputChange($event)"
                       :disabled="loading">
                <div class="input-group-append">
                  <button @click="onSubmit" class="btn btn-outline-success btn-sm" type="button"
                          :disabled="loading">
                    <i class="fas fa-check"></i>
                  </button>
                </div>
              </div>
              <small v-if="inputError" class="text-danger">
                <i class="fas fa-exclamation-triangle"></i> Digite um valor válido ou 0.<br>
              </small>
              <small v-if="stakeholder != 'manufacturer'" class="text-muted">
                Digite a quantidade de pedidos ao seu fornecedor e clique no botão para prosseguir a semana.
              </small>
              <small v-if="stakeholder == 'manufacturer'" class="text-muted">
                Digite a quantidade de produtos que queira colocar em produção e clique no botão para prosseguir a
                semana.
              </small>
            </li>
            <li class="list-group-item">
              <span v-if="stakeholder != 'manufacturer'"><i class="fas fa-sync-alt"></i> Processando pedidos</span>
              <span v-if="stakeholder == 'manufacturer'"><i class="fas fa-users-cog"></i> 1ª etapa de produção</span>
              <span class="badge badge-info badge-size float-right">{{data.incomingWeekTwo}}</span> <br>
              <small v-if="stakeholder != 'manufacturer'" class="text-muted">
                O fornecedor leva uma semana para processar seu pedido.
              </small>
            </li>
            <li class="list-group-item">
              <span v-if="stakeholder != 'manufacturer'"><i class="fas fa-truck-moving"></i> Em transito</span>
              <span v-if="stakeholder == 'manufacturer'"><i class="fas fa-users-cog"></i> 2ª etapa de produção</span>
              <span class="badge badge-warning text-white badge-size float-right">{{data.incomingWeekTwo}}</span> <br>
              <small v-if="stakeholder != 'manufacturer'" class="text-muted">
                O fornecedor leva uma semana para entregar seu pedido.
              </small>
            </li>
            <li v-if="stakeholder == 'manufacturer'" class="list-group-item">
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
  export default {
    props: ['stakeholder', 'gameId', 'dataUrl', 'submitUrl', 'nextWeekUrl'],
    mounted() {
      this.loadData();
      switch (this.stakeholder) {
        case 'retailer':
          return console.log('entrou no switch');
        case 'wholesaler':
          Echo.channel('').listen('', (e) => {
            if (this.yourOrder) {

            }
          });
          break;
        case 'distributor':
          Echo.channel('').listen('', (e) => {
            if (this.yourOrder) {

            }
          });
          Echo.channel(`ManufacturerWeekEvent.${this.gameId}`)
            .listen('ManufacturerWeekEvent', e => {
              console.log('ManufacturerWeekLogEvent', e);
              this.loadData();
            });
          break;
        case 'manufacturer':
          Echo.channel(`ManufacturerNewOrderEvent.${this.gameId}`)
            .listen('ManufacturerNewOrderEvent', (e) => {
              console.log('ManufacturerNewOrderEvent', e);
              this.newOrder = true;
              if (this.yourOrder && !this.callNextWeek) {
                this.nextWeek();
              }
            });
          Echo.channel(`ManufacturerWeekEvent.${this.gameId}`)
            .listen('ManufacturerWeekEvent', e => {
              console.log('ManufacturerWeekLogEvent', e);
              this.loadData();
            });
          break;
        default:
          break;
      }
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
        callNextWeek: false,
        yourOrder: false,
        newOrder: false
      }
    },
    computed: {
      provider() {
        switch (this.stakeholder) {
          case 'retailer':
            return 'Pedidos ao Atacadista';
          case 'wholesaler':
            return 'Pedidos ao Distribuidor';
          case 'distributor':
            return 'Pedidos ao Fabricante';
          case 'manufacturer':
            return 'Processo de produção';
          default:
            return '';
        }
      }
    },
    methods: {
      loadData() {
        axios.get(this.dataUrl).then(res => this.data = res.data).catch(error => console.log(error));
        this.loading = false;
        this.callNextWeek = false;
        this.yourOrder = false;
        this.newOrder = false;
      },
      nextWeek() {
        axios.post(this.nextWeekUrl, {}).then(res => this.callNextWeek = true).catch(error => console.log(error));
      },
      onSubmit() {
        this.loading = true;
        axios.post(this.submitUrl, {your_order: this.inputYourOrder})
          .then(res => {
            this.yourOrder = true;
            if (this.newOrder && !this.callNextWeek) {
              this.nextWeek();
            }
          })
          .catch(error => {
            this.loading = false;
            console.log(error);
          });
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
      graphic() {
        console.log('Gráfico');
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