/* Oasis Dune – validação em tempo real (contadores e condições dos campos).
   É apenas uma ajuda ao utilizador: a validação que conta é a do servidor. */
(function () {
  'use strict';

  var REGRA_NOME = /^[\p{L}\p{N}_.\-\s]+$/u;
  var REGRA_EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var codificador = new TextEncoder();

  // O servidor mede as palavras-passe e o e-mail em bytes (strlen) e o nome em caracteres.
  function medir(valor, medida) {
    return medida === 'bytes' ? codificador.encode(valor).length : Array.from(valor).length;
  }

  function avaliar(input) {
    var valor = input.value;
    var min   = parseInt(input.dataset.min || '0', 10);
    var max   = parseInt(input.dataset.max || '0', 10);
    var tam   = medir(valor, input.dataset.medida);
    var regra = input.dataset.regra;
    var msg   = '';

    if (tam < min) {
      msg = 'Faltam ' + (min - tam) + ' caracter' + (min - tam === 1 ? '' : 'es') + ' (mínimo ' + min + ').';
    } else if (max && tam > max) {
      msg = 'Máximo de ' + max + ' caracteres.';
    } else if (regra === 'nome' && !REGRA_NOME.test(valor)) {
      msg = 'Use apenas letras, números, _ . ou -.';
    } else if (regra === 'email' && !REGRA_EMAIL.test(valor)) {
      msg = 'Indique um e-mail válido.';
    } else if (regra === 'repetir') {
      var original = document.getElementById(input.dataset.igualA);
      if (original && valor !== original.value) { msg = 'As palavras-passe não coincidem.'; }
    }
    return { tam: tam, max: max, msg: msg };
  }

  function atualizar(input) {
    var campo    = input.closest('.campo');
    var r        = avaliar(input);
    var contador = campo.querySelector('.dica-contador');
    var texto    = campo.querySelector('.dica-texto');
    var mostrar  = input.dataset.tocado === '1' || input.value !== '';
    var invalido = mostrar && r.msg !== '';
    var valido   = mostrar && r.msg === '';

    if (contador && r.max) { contador.textContent = r.tam + '/' + r.max; }

    if (texto) {
      if (!texto.dataset.padrao) { texto.dataset.padrao = texto.textContent; }
      texto.textContent = invalido ? r.msg : (valido && input.dataset.ok ? input.dataset.ok : texto.dataset.padrao);
    }

    campo.classList.toggle('valido', valido);
    campo.classList.toggle('invalido', invalido);
    input.setAttribute('aria-invalid', invalido ? 'true' : 'false');
    input.setCustomValidity(r.msg);
  }

  document.querySelectorAll('.auth-form input[data-regra]').forEach(function (input) {
    input.addEventListener('input', function () {
      atualizar(input);
      if (input.dataset.regra === 'senha') {          // revalida o campo "repetir"
        var repetir = document.querySelector('[data-igual-a="' + input.id + '"]');
        if (repetir && repetir.value !== '') { atualizar(repetir); }
      }
    });
    input.addEventListener('blur', function () {
      input.dataset.tocado = '1';
      atualizar(input);
    });
    if (input.value !== '') { atualizar(input); }       // valores repostos pelo servidor
  });

  // Botão mostrar / ocultar palavra-passe
  document.querySelectorAll('.btn-olho').forEach(function (botao) {
    botao.addEventListener('click', function () {
      var input = document.getElementById(botao.dataset.alvo);
      var oculto = input.type === 'password';
      input.type = oculto ? 'text' : 'password';
      botao.textContent = oculto ? 'Ocultar' : 'Mostrar';
      botao.setAttribute('aria-pressed', oculto ? 'true' : 'false');
    });
  });
})();
