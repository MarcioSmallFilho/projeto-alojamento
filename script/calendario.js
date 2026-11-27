let calendarPc = null;
let calendarTll = null;

// ==========================================
// RESUMO DA RESERVA (preencher -> resumo -> confirmar -> enviar)
// ==========================================

function iniciarResumoReserva() {

    const form = document.getElementById('bookingForm');
    const modal = document.getElementById('modalReserva');
    if (!form || !modal) return;   // nunca deixa um erro aqui partir o calendario

    const btnVoltar = document.getElementById('voltarReserva');
    const btnConfirmar = document.getElementById('confirmarPedido');
    const campoConfirmar = document.getElementById('confirmarReserva');
    if (!btnVoltar || !btnConfirmar || !campoConfirmar) return;

    const precoNoite = parseFloat(form.dataset.precoNoite) || 0;
    const limite = parseInt(form.dataset.limite, 10) || Infinity;
    const admin = form.dataset.admin === '1';

    const euros = new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' });
    const formatarData = iso => iso.split('-').reverse().join('/');
    const noitesEntre = (a, b) =>
        Math.round((Date.parse(b + 'T00:00:00Z') - Date.parse(a + 'T00:00:00Z')) / 86400000);
    const escrever = (id, texto) => { document.getElementById(id).textContent = texto; };

    if (admin) {
        modal.querySelector('h2').textContent = 'Confirmar bloqueio';
        modal.querySelector('.subtitulo-reserva').textContent = 'Confirma as datas que vais bloquear.';
        modal.querySelector('.resumo-preco').style.display = 'none';
        btnConfirmar.textContent = 'Confirmar bloqueio';
    }

    function fechar() {
        modal.style.display = 'none';
        campoConfirmar.value = '0';
        btnConfirmar.disabled = false;
    }

    // 1) Clicou em "Fazer pedido de reserva": mostra o resumo, NAO envia
    form.addEventListener('submit', function(e) {

        if (campoConfirmar.value === '1') return;   // envio final, depois de confirmar

        const checkin = form.elements['checkin'].value;
        const checkout = form.elements['checkout'].value;
        const adultos = parseInt(form.elements['numberOfAdults'].value, 10);
        const criancas = parseInt(form.elements['numberOfChildren'].value, 10);
        const bebes = parseInt(form.elements['numberOfInfants'].value, 10);
        const noites = (checkin && checkout) ? noitesEntre(checkin, checkout) : 0;

        const valido = noites > 0
            && adultos >= 1 && criancas >= 0 && bebes >= 0
            && (adultos + criancas + bebes) <= limite;

        // Dados invalidos: deixa seguir para o servidor, que mostra o erro.
        // Como confirmarReserva = 0, o servidor nunca grava nada.
        if (!valido) return;

        e.preventDefault();

        escrever('resumoCheckin', formatarData(checkin));
        escrever('resumoCheckout', formatarData(checkout));
        escrever('resumoNoites', noites);
        escrever('resumoAdultos', adultos);
        escrever('resumoCriancas', criancas);
        escrever('resumoBebes', bebes);
        escrever('resumoPrecoNoite', euros.format(precoNoite));
        escrever('resumoPrecoTotal', euros.format(precoNoite * noites));

        modal.style.display = 'flex';
    });

    // 2) "Voltar e editar" / clicar fora / Esc: fecha sem enviar
    btnVoltar.addEventListener('click', fechar);
    modal.addEventListener('click', e => { if (e.target === modal) fechar(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.style.display === 'flex') fechar();
    });

    // 3) "Confirmar e enviar pedido": so aqui o formulario segue para o servidor
    btnConfirmar.addEventListener('click', function() {
        campoConfirmar.value = '1';
        btnConfirmar.disabled = true;   // evita duplo clique = duas reservas
        form.submit();
    });
}

document.addEventListener('DOMContentLoaded', function() {

    // Evita reenviar o formulario (e duplicar a reserva) ao atualizar a pagina com F5
    if (window.history.replaceState) {
        window.history.replaceState(null, '', window.location.href);
    }

    iniciarResumoReserva();

    // Elementos do formulário
    const inputCheckIn = document.querySelector('input[name="checkin"]');
    const inputCheckOut = document.querySelector('input[name="checkout"]');

    // PHP que vai buscar as reservas à base de dados
    const calendarEventsSource = '../PHP/config.php';

    // ==========================================
    // PREENCHER FORMULÁRIO
    // ==========================================

    function preencherFormularioReserva(info) {

        if (inputCheckIn) {
            inputCheckIn.value = info.startStr;
        }

        const dataFimAjustada = new Date(info.end);

        dataFimAjustada.setDate(dataFimAjustada.getDate() - 1);

        const ano = dataFimAjustada.getFullYear();
        const mes = String(dataFimAjustada.getMonth() + 1).padStart(2, '0');
        const dia = String(dataFimAjustada.getDate()).padStart(2, '0');

        if (inputCheckOut) {
            inputCheckOut.value = `${ano}-${mes}-${dia}`;
        }
    }

    // ==========================================
    // 1. CALENDÁRIO DESKTOP
    // ==========================================

    const calendarElPc = document.getElementById('meu-calendario-pc');

    if (calendarElPc) {

        calendarPc = new FullCalendar.Calendar(calendarElPc, {
            height: 'auto',        // ajusta à quantidade de conteúdo
            contentHeight: 350,    // altura fixa do conteúdo
            aspectRatio: 1.35,     // proporção largura/altura

            initialView: 'dayGridMonth',
            locale: 'pt',
            firstDay: 1,

            headerToolbar: {
                left: 'prev,today,next',
                center: 'title',
                right: ''
            },

            selectable: true,
            unselectAuto: false,

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
    // 2. CALENDÁRIO MOBILE
    // ==========================================

    const calendarElTll = document.getElementById('meu-calendario-tll');

    if (calendarElTll) {

        calendarTll = new FullCalendar.Calendar(calendarElTll, {
            height: '80%',        // ajusta à quantidade de conteúdo

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

// ==========================================
// 3. ATUALIZAR RESERVAS AUTOMATICAMENTE
// ==========================================

setInterval(function() {

    if (calendarPc) {
        calendarPc.refetchEvents();
    }

    if (calendarTll) {
        calendarTll.refetchEvents();
    }

}, 300000);