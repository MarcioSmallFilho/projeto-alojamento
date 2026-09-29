document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('meu-calendario-pc');
  
  // Seleciona os campos do teu formulário HTML
  const inputCheckIn = document.querySelector('input[name="checkin"]');
  const inputCheckOut = document.querySelector('input[name="checkout"]');
  const form = document.getElementById('bookingForm');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'pt',
    firstDay: 1,
    headerToolbar: {
      left: 'prev,today,next',
      center: 'title',
      right: 'dayGridMonth multiMonthYear'
    },
    footerToolbar: {
      left: 'prev,today,next',
      center: '',
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

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('meu-calendario-tll');

  const inputCheckIn = document.querySelector('input[name="checkin"]');
  const inputCheckOut = document.querySelector('input[name="checkout"]');
  const form = document.getElementById('bookingForm');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    // 1. Adaptação Mobile: Usa a vista de mês simples ou lista
    initialView: window.innerWidth < 768 ? 'dayGridMonth' : 'dayGridMonth',
    
    // 2. Altura ajustada para não ocupar o ecrã todo no telemóvel
    height: 'auto',
    contentHeight: 'auto',
    aspectRatio: window.innerWidth < 768 ? 0.85 : 1.35,

    // 3. Barra superior simplificada
    headerToolbar: {
      left: 'prev',
      center: 'title',
      right: 'next'
    },

    // 4. Seleção de datas ativada (para escolher Check-in e Check-out)
    selectable: true,
    selectLongPressDelay: 100, // Facilita o clique prolongado/toque no telemóvel

    // 5. Impedir reserva de datas passadas ou indisponíveis
    selectAllow: function(selectInfo) {
      var today = new Date();
      today.setHours(0,0,0,0);
      return selectInfo.start >= today;
    },

    // 6. Evento ao selecionar datas (quando o cliente clica nas datas de estadia)
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

    // 7. Datas já ocupadas/reservadas (exemplo de datas bloqueadas)
    events: [
      {
        title: 'Ocupado',
        start: '2026-10-10',
        end: '2026-10-15',
        color: '#ff4d4d' // Vermelho para dias indisponíveis
      }      
    ],
    selectOverlap: false,

    // 8. Ajusta ao rodar o telemóvel
    windowResize: function() {
      if (window.innerWidth < 768) {
        calendar.setOption('aspectRatio', 0.85);
      } else {
        calendar.setOption('aspectRatio', 1.35);
      }
    }
  });

  calendar.render();
});