/*
 * Preenche as imagens de uma página com as imagens associadas no sistema de secções.
 *
 * Cada espaço na página marca-se com:
 *   data-secao="Nome da secção"   (obrigatório, igual ao nome na tabela Secao)
 *   data-pos="0"                  (posição na lista da secção; 0 = destaque / primeira)
 *
 * Onde o endereço da imagem é colocado:
 *   <img>, <source>, <video>, ...  -> atributo src
 *   <a>, <link>, <area>            -> atributo href
 *   data-fundo                     -> background-image (para qualquer elemento)
 *   data-atributo="poster"         -> força outro atributo (só src, href ou poster)
 *
 * O <body> indica o alojamento e a imagem alternativa:
 *   data-alojamento="1"
 *   data-sem-imagem="../imagens/sem-imagem.svg"
 *
 * Se faltar a associação, o pedido falhar ou o ficheiro não existir,
 * o espaço fica com o que já tem no HTML (a imagem alternativa ou o href original).
 */
(function () {
    'use strict';

    const ENDPOINT = '../PHP/imagens-secao.php';
    const BASE = '../'; // pasta do projeto vista a partir de paginas/

    const ATRIBUTOS_PERMITIDOS = ['src', 'href', 'poster'];
    const ATRIBUTO_POR_ETIQUETA = { A: 'href', LINK: 'href', AREA: 'href' };

    const semImagem = document.body.dataset.semImagem || '';
    const alojamento = document.body.dataset.alojamento;

    const espacos = Array.from(document.querySelectorAll('[data-secao]'));

    if (!espacos.length || !alojamento) {
        return;
    }

    function urlDaImagem(caminho) {
        return BASE + caminho.split('/').map(encodeURIComponent).join('/');
    }

    // Decide qual o atributo a preencher neste elemento
    function atributoDoEspaco(espaco) {
        const pedido = espaco.dataset.atributo;

        if (pedido && ATRIBUTOS_PERMITIDOS.indexOf(pedido) !== -1) {
            return pedido;
        }

        return ATRIBUTO_POR_ETIQUETA[espaco.tagName] || 'src';
    }

    function aplicarImagem(espaco, url) {
        const fundo = espaco.hasAttribute('data-fundo');
        const atributo = atributoDoEspaco(espaco);

        // Fundos CSS
        if (fundo) {
            const teste = new Image();

            teste.onload = function () {
                espaco.style.backgroundImage = 'url("' + url + '")';
            };

            teste.src = url;
            return;
        }

        // Links, ícones, scripts e outros elementos que utilizam href
        if (atributo === 'href') {
            espaco.setAttribute('href', url);
            return;
        }

        // Imagens normais
        if (atributo === 'src' && espaco.tagName === 'IMG') {
            espaco.onerror = function () {
                espaco.onerror = null;
                espaco.src = semImagem;
            };

            espaco.src = url;
            return;
        }

        // Vídeos e outros elementos que utilizam src ou poster
        espaco.setAttribute(atributo, url);
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