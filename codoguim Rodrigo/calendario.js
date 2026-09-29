document.addEventListener('DOMContentLoaded', function () {

// =========================
// CALENDÁRIO PC
// =========================

const calendarElPC = document.getElementById('meu-calendario-pc');

if (calendarElPC) {
    const inputCheckIn = document.querySelector('input[name="checkin"]');
    const inputCheckOut = document.querySelector('input[name="checkout"]');
    const form = document.getElementById('bookingForm')
    const calendarPC = new FullCalendar.Calendar(calendarElPC, {initialView: 'dayGridMonth',locale: 'pt',firstDay: 1,headerToolbar: {
            left: 'prev,today,next',
            center: 'title',
            right: 'dayGridMonth,multiMonthYear'
        },
        footerToolbar: {
            left: 'prev,today,next',
            center: '',
            right: ''
        },
        selectable: true,
        unselectAuto: false,
        // =========================
        // EVENTOS DO PHP
        // =========================
        events: '../calendario.php',  
        selectOverlap: false,
        // =========================
        // SELEÇÃO DE DATAS
        // =========================
        selectAllow: function (selectInfo) {
            const hoje = new Date();
            hoje.setHours(0, 0, 0, 0);
            return selectInfo.start >= hoje;
        },
        select: function (info) {
            if (inputCheckIn) {
                inputCheckIn.value = info.startStr;
            }
            const dataFimAjustada = new Date(info.end);
            dataFimAjustada.setDate(
                dataFimAjustada.getDate() - 1
            );
            const ano = dataFimAjustada.getFullYear();
            const mes = String(
                dataFimAjustada.getMonth() + 1
            ).padStart(2, '0');
            const dia = String(
                dataFimAjustada.getDate()
            ).padStart(2, '0');
            if (inputCheckOut) {
                inputCheckOut.value = `${ano}-${mes}-${dia}`;
            }
        }
    });
    calendarPC.render();
}
// =========================
// CALENDÁRIO TELEMÓVEL
// =========================
const calendarElMobile = document.getElementById('meu-calendario-tll');
if (calendarElMobile) {
    const inputCheckIn = document.querySelector('input[name="checkin"]');
    const inputCheckOut = document.querySelector('input[name="checkout"]');
    const form = document.getElementById('bookingForm');
    const calendarMobile = new FullCalendar.Calendar(calendarElMobile, {
        initialView: 'dayGridMonth',
        locale: 'pt',
        firstDay: 1,
        height: 'auto',
        contentHeight: 'auto',
        aspectRatio: window.innerWidth < 768
            ? 0.85
            : 1.35,
        headerToolbar: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        selectable: true,
        selectLongPressDelay: 100,
        // =========================
        // EVENTOS DO PHP
        // =========================
        events: '../calendario.php',
        selectOverlap: false,
        // =========================
        // IMPEDIR DATAS PASSADAS
        // =========================
        selectAllow: function (selectInfo) {
            const hoje = new Date();
            hoje.setHours(0, 0, 0, 0);
            return selectInfo.start >= hoje;
        },
        // =========================
        // SELEÇÃO
        // =========================
        select: function (info) {
            if (inputCheckIn) {
                inputCheckIn.value = info.startStr;
            }
            const dataFimAjustada = new Date(info.end);
            dataFimAjustada.setDate(
                dataFimAjustada.getDate() - 1
            );
            const ano = dataFimAjustada.getFullYear();
            const mes = String(
                dataFimAjustada.getMonth() + 1
            ).padStart(2, '0');
            const dia = String(
                dataFimAjustada.getDate()
            ).padStart(2, '0');
            if (inputCheckOut) {
                inputCheckOut.value = `${ano}-${mes}-${dia}`;
            }
        },
        // =========================
        // RESPONSIVO
        // =========================
        windowResize: function () {
            if (window.innerWidth < 768) {
                calendarMobile.setOption(
                    'aspectRatio',
                    0.85
                );
            } else {
                calendarMobile.setOption(
                    'aspectRatio',
                    1.35
                );
            }
        }
    });
    calendarMobile.render();
}
});