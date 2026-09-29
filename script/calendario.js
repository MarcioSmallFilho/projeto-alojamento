document.addEventListener('DOMContentLoaded', function() {
  // Elementos do formulário
  const inputCheckIn = document.querySelector('input[name="checkin"]');
  const inputCheckOut = document.querySelector('input[name="checkout"]');
  const form = document.getElementById('bookingForm');

  // Aponta diretamente para o config.php (que agora envia JSON limpo)
  const calendarEventsSource = '../config.php';

  // Função auxiliar para preencher os inputs ao selecionar datas
  function preencherFormularioReserva(info) {
    if (inputCheckIn) inputCheckIn.value = info.startStr;

    // Ajusta a data final (-1 dia) para refletir a noite de checkout real
    const dataFimAjustada = new Date(info.end);
    dataFimAjustada.setDate(dataFimAjustada.getDate() - 1);

    const ano = dataFimAjustada.getFullYear();
    const mes = String(dataFimAjustada.getMonth() + 1).padStart(2, '0');
    const dia = String(dataFimAjustada.getDate()).padStart(2, '0');

    if (inputCheckOut) inputCheckOut.value = `${ano}-${mes}-${dia}`;

    if (form) form.scrollIntoView({ behavior: 'smooth' });
  }

  // ==========================================
  // 1. CALENDÁRIO DESKTOP (#meu-calendario-pc)
  // ==========================================
  const calendarElPc = document.getElementById('meu-calendario-pc');
  if (calendarElPc) {
    const calendarPc = new FullCalendar.Calendar(calendarElPc, {
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
      selectable: true,
      unselectAuto: false,

      // USA O JSON FEED DIRETO DO PHP
      events: calendarEventsSource,
      selectOverlap: false,

      selectAllow: function(selectInfo) {
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);
        return selectInfo.start >= hoje;
      },

      select: preencherFormularioReserva
    });

    calendarPc.render();
  }

  // ==========================================
  // 2. CALENDÁRIO MOBILE (#meu-calendario-tll)
  // ==========================================
  const calendarElTll = document.getElementById('meu-calendario-tll');
  if (calendarElTll) {
    const calendarTll = new FullCalendar.Calendar(calendarElTll, {
      initialView: 'dayGridMonth',
      locale: 'pt',
      firstDay: 1,
      height: 'auto',
      contentHeight: 'auto',
      aspectRatio: window.innerWidth < 768 ? 0.85 : 1.35,

      headerToolbar: {
        left: 'prev',
        center: 'title',
        right: 'next'
      },

      selectable: true,
      selectLongPressDelay: 100,

      // USA O JSON FEED DIRETO DO PHP
      events: calendarEventsSource,
      selectOverlap: false,

      selectAllow: function(selectInfo) {
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);
        return selectInfo.start >= hoje;
      },

      select: preencherFormularioReserva,

      windowResize: function() {
        if (window.innerWidth < 768) {
          calendarTll.setOption('aspectRatio', 0.85);
        } else {
          calendarTll.setOption('aspectRatio', 1.35);
        }
      }
    });

    calendarTll.render();
  }
});

setInterval(function() {
  if (typeof calendarPc !== 'undefined') calendarPc.refetchEvents();
  if (typeof calendarTll !== 'undefined') calendarTll.refetchEvents();
}, 300000);