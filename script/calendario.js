document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('meu-calendario');
  
  // Seleciona os campos do teu formulário HTML
  const inputCheckIn = document.querySelector('input[name="checkin"]');
  const inputCheckOut = document.querySelector('input[name="checkout"]');
  const form = document.getElementById('bookingForm');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'pt',
    firstDay: 1,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: ''
    },
    
    // ATIVA A SELEÇÃO NO CALENDÁRIO
    selectable: true,
    unselectAuto: false, // Mantém o destaque visual dos dias escolhidos

    // Quando o utilizador clica/arrasta nas datas
    select: function(info) {
        // Data de Check-in (início)
        inputCheckIn.value = info.startStr;

        // Ajustar a data de Check-out para subtrair 1 dia
        const dataFimAjustada = new Date(info.end);
        dataFimAjustada.setDate(dataFimAjustada.getDate() - 1);

        // Formatar para o formato YYYY-MM-DD
        const ano = dataFimAjustada.getFullYear();
        const mes = String(dataFimAjustada.getMonth() + 1).padStart(2, '0');
        const dia = String(dataFimAjustada.getDate()).padStart(2, '0');

        inputCheckOut.value = `${ano}-${mes}-${dia}`;

        // Scroll suave até ao formulário
        form.scrollIntoView({ behavior: 'smooth' });
    },

    // Impede selecionar datas passadas
    selectAllow: function(selectInfo) {
      const hoje = new Date();
      hoje.setHours(0, 0, 0, 0);
      return selectInfo.start >= hoje;
    },

    // EVENTOS/RESERVAS (Podes substituir futuramente pelo iCal)
    events: [
      {
        title: 'Ocupado',
        start: '2026-10-10',
        end: '2026-10-15',
        color: '#e74c3c',
        allDay: true
      }
    ],
    selectOverlap: false // Impede selecionar sobre dias já ocupados
  });

  calendar.render();
});