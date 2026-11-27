<?php

/**
 * Notificações isoladas do resto do site (Shadow DOM).
 *
 * O CSS das notificações vive dentro de um "shadow root", por isso:
 *  - nenhum CSS da página (style.css, styleCal.css, !important, etc.) as afeta;
 *  - o CSS das notificações também não vaza para a página;
 *  - o elemento é anexado ao <html>, fora de qualquer pai com transform,
 *    filter, backdrop-filter ou mask (que quebram o position: fixed).
 */
function notificacao($mensagem, $tipo = "erro") {

    static $baseInjetada = false;

    $cores = [
        "erro"    => "#ef4444",
        "sucesso" => "#22c55e",
        "aviso"   => "#f59e0b",
        "info"    => "#3b82f6",
    ];

    $icones = [
        "erro"    => "✖",
        "sucesso" => "✔",
        "aviso"   => "⚠",
        "info"    => "ℹ",
    ];

    if (!isset($cores[$tipo])) {
        $tipo = "erro";
    }

    // Dados enviados ao JavaScript em JSON seguro (sem risco de XSS).
    $dados = json_encode([
        "titulo"   => ucfirst($tipo),
        "mensagem" => (string) $mensagem,
        "cor"      => $cores[$tipo],
        "icone"    => $icones[$tipo],
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

    // O CSS e a função base só são escritos na primeira chamada.
    if (!$baseInjetada) {
        $baseInjetada = true;
        echo <<<'HTML'
<script>
(function () {
    if (window.mostrarNotificacao) return;

    var CSS = `
        :host { all: initial; }
        .pilha {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 320px;
            max-width: calc(100vw - 40px);
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-family: Arial, sans-serif;
            pointer-events: none;
        }
        .notificacao {
            pointer-events: auto;
            box-sizing: border-box;
            background: #1f2937;
            color: #fff;
            border-left: 5px solid var(--cor);
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,.25);
            animation: entrar .4s ease;
        }
        .notificacao.sair { animation: sair .4s forwards; }
        .icone { color: var(--cor); font-size: 24px; }
        .conteudo { flex: 1; min-width: 0; }
        strong { font-size: 15px; }
        p { color: #fff; margin: 5px 0 0; font-size: 13px; overflow-wrap: anywhere; }
        button {
            background: none;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
        }
        @keyframes entrar { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        @keyframes sair   { to   { opacity: 0; transform: translateX(100%); } }
    `;

    function pilha() {
        var host = document.getElementById('notificacoes-host');
        if (!host) {
            host = document.createElement('notificacoes-host');
            host.id = 'notificacoes-host';
            // Fica acima de tudo, independentemente do CSS da página.
            host.style.setProperty('position', 'fixed', 'important');
            host.style.setProperty('inset', '0', 'important');
            host.style.setProperty('z-index', '2147483647', 'important');
            host.style.setProperty('pointer-events', 'none', 'important');
            host.style.setProperty('display', 'block', 'important');
            var raiz = host.attachShadow({ mode: 'closed' });
            var estilo = document.createElement('style');
            estilo.textContent = CSS;
            var p = document.createElement('div');
            p.className = 'pilha';
            raiz.append(estilo, p);
            host._pilha = p;
            // Anexado ao <html>, fora do <body> e de qualquer pai com transform/filter.
            document.documentElement.appendChild(host);
        }
        return host._pilha;
    }

    window.mostrarNotificacao = function (d) {
        var el = document.createElement('div');
        el.className = 'notificacao';
        el.style.setProperty('--cor', d.cor);

        var icone = document.createElement('div');
        icone.className = 'icone';
        icone.textContent = d.icone;

        var conteudo = document.createElement('div');
        conteudo.className = 'conteudo';
        var titulo = document.createElement('strong');
        titulo.textContent = d.titulo;
        var texto = document.createElement('p');
        texto.textContent = d.mensagem; // textContent = sem HTML, sem XSS
        conteudo.append(titulo, texto);

        var fechar = document.createElement('button');
        fechar.type = 'button';
        fechar.textContent = '×';

        el.append(icone, conteudo, fechar);

        function remover() {
            el.classList.add('sair');
            setTimeout(function () { el.remove(); }, 400);
        }
        fechar.addEventListener('click', remover);
        setTimeout(remover, 4000);

        pilha().appendChild(el);
    };
})();
</script>
HTML;
    }

    echo "<script>(function(){var d=$dados;"
       . "if(document.documentElement){window.mostrarNotificacao(d);}"
       . "else{document.addEventListener('DOMContentLoaded',function(){window.mostrarNotificacao(d);});}"
       . "})();</script>\n";
}