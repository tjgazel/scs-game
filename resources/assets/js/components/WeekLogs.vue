<template>
  <div class="container-fluid">
    <div class="row mt-3 mb-5">
      <div class="col">
        <div class="card text-center">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item">
                <a class="nav-link active" id="graphics-tab" data-toggle="tab" href="#graphics" aria-controls="graphics"
                   aria-selected="true">
                  <i class="fas fa-chart-line"></i> Gráficos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" aria-controls="history"
                   aria-selected="true">
                  <i class="fa fa-calendar-alt"></i> Histórico semanal
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body px-1">
            <div class="tab-content" id="myTabContent">
              <div class="row tab-pane fade show active" id="graphics" role="tabpanel" aria-labelledby="graphics-tab">
                <div class="col-12 mb-5">
                  <graphics :chart-data="chartData"></graphics>
                </div>
                <div class="col-12">
                  <graphics :chart-data="costData"></graphics>
                </div>
              </div>
              <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="table-responsive bg-white">
                  <table class="table table-sm">
                    <thead class="thead-light">
                    <tr>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-calendar"></i><br>Semana
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-sync-alt"></i><br>Seus<br>pedidos
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-people-carry"></i><br>Entrada<br>estoque
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-shopping-cart"></i><br>Pedidos<br>de clientes
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-truck-loading"></i><br>Pedidos<br>para entregar
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-truck"></i><br>Pedidos<br>enviados
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-user-clock"></i><br>Novos pedidos<br>em atraso
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-cubes"></i><br>Estoque<br>atualizado
                      </th>
                      <th scope="col" class="text-center align-middle">
                        <i class="fa fa-money-bill-alt"></i><br>Custos
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in data">
                      <td class="text-center">{{index}}</td>
                      <td class="text-center">{{item.your_order}}</td>
                      <td class="text-center">{{item.incoming}}</td>
                      <td class="text-center">{{item.new_order}}</td>
                      <td class="text-center">{{item.to_ship}}</td>
                      <td class="text-center">{{item.delivery}}</td>
                      <td class="text-center">{{item.back_order}}</td>
                      <td class="text-center">{{item.inventory}}</td>
                      <td class="text-left">{{item.cost | filterMoney}}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Graphics from './Graphics';

  export default {
    components: {Graphics},
    props: ['gameId', 'dataUrl'],
    data() {
      return {
        data: [{
          id: 0,
          retailer_id: 0,
          incoming: 0,
          available: 0,
          new_order: 0,
          to_ship: 0,
          delivery: 0,
          back_order: 0,
          inventory: 0,
          your_order: 0,
          cost: 0
        }]
      }
    },
    computed: {
      label() {
        let _count = 0, _label = [];
        this.data.forEach(e => {
          _label.push(_count);
          _count++;
        });
        return _label;
      },
      newOrder() {
        let _newOrder = [];
        this.data.forEach(e => _newOrder.push(e.new_order));
        return _newOrder;
      },
      yourOrder() {
        let _yourOrder = [];
        this.data.forEach(e => _yourOrder.push(e.your_order));
        return _yourOrder;
      },
      cost() {
        let _cost = [];
        this.data.forEach(e => _cost.push(e.cost));
        return _cost;
      },
      chartData() {
        return {
          labels: this.label,
          datasets: [
            {
              label: 'Pedidos de Clientes',
              borderColor: 'rgba(0, 255, 84, 1)',
              backgroundColor: 'rgba(0, 255, 84, 0.1)',
              data: this.newOrder
            },
            {
              label: 'Pedidos ao Fornecedor',
              borderColor: 'rgba(54, 162, 235, 1)',
              backgroundColor: 'rgba(54, 162, 235, 0.1)',
              data: this.yourOrder
            }
          ]
        }
      },
      costData() {
        return {
          labels: this.label,
          datasets: [
            {
              label: 'Custos de manutenção',
              borderColor: 'rgba(255,0,0, 1)',
              backgroundColor: 'rgba(255,0,0, 0.4)',
              data: this.cost
            }
          ]
        }
      }
    },
    mounted() {
      this.loadData();

      Echo.channel(`RetailerWeekEvent.${this.gameId}`)
        .listen('RetailerWeekEvent', (e) => {
          this.loadData();
        });
    },
    methods: {
      loadData() {
        axios.get(this.dataUrl).then(res => this.data = res.data).catch(error => console.log(error));
      }
    },
    filters: {
      filterMoney(value) {
        if (!value) return '';
        return new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(value);
      }
    }
  }
</script>