@extends('layouts.base')
@section('content')
<div class="row">
  <div class="col">
    <h3>Bem vindo(a) !</h3>
    <p>Supply Chain Simulator (SCS) Game, é um jogo que simula a cadeia de abastecimento e distribuição de uma empresa
      .</p>
    <p>
      Como um dos stakeholders industriais, seu objetivo é manter um bom equilíbrio entre a demanda de pedidos que você recebe
      dos seus clientes, com os pedidos feitos aos seus fornecedores.
    </p>
    <p>Certifique-se de manter um nível razoável de estoque e nenhum pedido com atrasado, pois eles geram custos.</p>
  </div>
</div>

<div class="row">
  <div class="col text-center">
    <img class="img-fluid" src="{{ asset('images/scheme.svg') }}" alt="">
  </div>
</div>

@endsection()