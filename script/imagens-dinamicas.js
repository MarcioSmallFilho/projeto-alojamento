/*
 * Preenche as imagens de uma página com as imagens associadas no sistema de secções.
 *
 * Cada espaço de imagem na página marca-se com:
 *   data-secao="Nome da secção"   (obrigatório, igual ao nome na tabela Secao)
 *   data-pos="0"                  (posição na lista da secção; 0 = destaque / primeira)
 *   data-fundo                    (só se a imagem for um background-image em vez de <img>)
 *
 * O <body> indica o alojamento e a imagem alternativa:
 *   data-alojamento="1"
 *   data-sem-imagem="../imagens/sem-imagem.svg"
 *
 * Se faltar a associação, o pedido falhar ou o ficheiro não existir,
 * o espaço fica com a imagem alternativa que já tem no HTML.
 */
(function () {
    'use strict';

    const ENDPOINT = '../PHP/imagens-secao.php';
    const BASE = '../'; // pasta do projeto vista a partir de paginas/

    const semImagem = document.body.dataset.semImagem || '';
    const alojamento = document.body.dataset.alojamento;

    const espacos = Array.from(document.querySelectorAll('[data-secao]'));

    if (!espacos.length || !alojamento) {
        return;
    }

    function urlDaImagem(caminho) {
        return BASE + caminho.split('/').map(encodeURIComponent).join('/');
    }

    function aplicarImagem(espaco, url) {
        if (espaco.hasAttribute('data-fundo')) {
            // Só troca o fundo depois de a imagem carregar; se falhar, fica a alternativa
            const teste = new Image();
            teste.onload = function () {
                espaco.style.backgroundImage = 'url("' + url + '")';
            };
            teste.src = url;
            return;
        }

        espaco.onerror = function () {
            espaco.onerror = null;
            espaco.src = semImagem;
        };
        espaco.src = url;
    }

    const parametros = new URLSearchParams();
    parametros.set('alojamento', alojamento);

    new Set(espacos.map(function (e) { return e.dataset.secao; }))
        .forEach(function (nome) { parametros.append('secoes[]', nome); });

    fetch(ENDPOINT + '?' + parametros.toString())
        .then(function (resposta) {
            return resposta.ok ? resposta.json() : Promise.reject(new Error('Pedido falhou'));
        })
        .then(function (dados) {
            const secoes = (dados && dados.secoes) || {};

            espacos.forEach(function (espaco) {
                const lista = secoes[espaco.dataset.secao] || [];
                const item = lista[Number(espaco.dataset.pos || 0)];

                if (item && item.caminho) {
                    aplicarImagem(espaco, urlDaImagem(item.caminho));
                }
            });
        })
        .catch(function () {
            // Sem resposta do endpoint: ficam as imagens alternativas
        });
})();
