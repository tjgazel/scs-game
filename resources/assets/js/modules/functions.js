export const randomInt = (min, max) => Match.floor(Match.random() * (max - min)) + min;

export const alertSubmit = (stakeholder) => {
  window.toastr.info(`${stakeholder} já fez o pedido!`, null, {closeButton: true, timeOut: 0});
};

export const alertSeason = (maxWeeks, week) => {
  if (week == (maxWeeks / 2) -1) {
    window.toastr.warning('Prepare-se para receber pedidos maiores nas próximas semanas.',
      'Alta temporada!',
      {closeButton: true, timeOut: 0}
    );
  }

  if ((maxWeeks == 52 && week == ((maxWeeks / 2) + 11)) || (maxWeeks == 26 && week == ((maxWeeks / 2) + 7))) {
    window.toastr.warning('Parece que o movimento voltou ao normal.',
      'Fim da Alta temporada!',
      {closeButton: true, timeOut: 0}
    );
  }
};