<template>
  <nav :class="['navbar', 'navbar-expand-lg', 'fixed-top', 'navbar-dark', {
                'bg-success':  stakeholder == 'retailer',
                'bg-secondary':  stakeholder == 'wholesaler',
                'bg-info':  stakeholder == 'distributor',
                'bg-primary':  stakeholder == 'manufacturer'
                }]">
    <div class="container">
      <a class="navbar-brand text-white">
        <i class="fas fa-play"></i> {{data.game.name}}&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>&nbsp;&nbsp;&nbsp;
        <i :class="['fa',{'fa-store-alt': stakeholder == 'retailer',
                          'fa-building':  stakeholder == 'wholesaler',
                          'fa-dolly-flatbed':  stakeholder == 'distributor',
                          'fa-industry':  stakeholder == 'manufacturer'}]"></i> {{stakeholderName}}
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler"
              aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarToggler">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link text-white" href="#" @click.prevent="changeStakeholder">
              <i class="fas fa-exchange-alt"></i> Alterar stakeholder
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#" @click.prevent="gameOut">
              <i class="fas fa-ban text-danger"></i> Sair da partida
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
  export default {
    props: ['stakeholder', 'dataJson', 'gameOutUrl'],
    data() {
      return {
        data: JSON.parse(this.dataJson)
      }
    },
    computed: {
      stakeholderName(){
        switch(this.stakeholder){
          case 'retailer': return 'Varejista';
          case 'wholesaler': return 'Atacadista';
          case 'distributor': return 'Distribuidor';
          case 'manufacturer': return 'Fabricante';
          default: return '';
        }
      }
    },
    methods: {
      changeStakeholder() {
          window.location = `/games/${this.data.game.id}`;
      },
      gameOut() {
        axios.post(this.gameOutUrl, {}).then(() => {
          window.location = '/games';
        });
      }
    }
  }
</script>
