function goto(params) {
    window.location.href = params
}
function gotoAIRBNB() {
    window.location.href = 'https://www.airbnb.pt/rooms/1193985038143849991?source_impression_id=p3_1790247193_P3dUzdkSFcTkuTYL'
}
function godown(id) {
    window.location.hash = id
}


const header = document.querySelector('header');

  const observer = new IntersectionObserver(
    ([entry]) => {
      // Adiciona .is-sticky quando deixa de estar no topo original
      header.classList.toggle('is-sticky', entry.intersectionRatio < 1);
    },
    { threshold: [1] }
  );

  observer.observe(header);

document.addEventListener('DOMContentLoaded', () => {
  const btnMenu = document.getElementById('btn-menu');
  const navMenu = document.getElementById('nav-menu');

  if (btnMenu && navMenu) {
    btnMenu.addEventListener('click', () => {
      navMenu.classList.toggle('aberto');
      btnMenu.classList.toggle('ativo'); // Alterna o botão entre ☰ e X
    });

    navMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('aberto');
        btnMenu.classList.remove('ativo');
      });
    });
  }
});

function toggleComodidades() {
    const lista = document.querySelector('.areaB .comodidades');
    const botao = document.getElementById('botaoMostrarMais');
    const extras = document.querySelectorAll('.areaB .comodidade.extra');
    const expandindo = !lista.classList.contains('expandida');

    if (expandindo) {
        // 1) Torna visível no layout (display: none -> inline-flex), ainda com opacity 0
        extras.forEach((item, i) => {
            item.classList.add('mostrando');
            item.style.transitionDelay = `${i * 0.05}s`;
        });

        // 2) Força o navegador a "registrar" esse estado antes de animar
        void lista.offsetHeight;

        // 3) Só agora adiciona a classe que dispara a transição de opacity/transform
        lista.classList.add('expandida');

    } else {
        lista.classList.remove('expandida');
        extras.forEach((item, i) => {
          const indiceInvertido = extras.length - 1 - i;
          item.style.transitionDelay = `${indiceInvertido * 0.03}s`;
        });

        //Espera a transição de saída terminar antes de sumir de vez (display: none)
        setTimeout(() => {
            extras.forEach(item => item.classList.remove('mostrando'));
        }, 600); // bate com o "0.35s" do CSS
    }

    botao.classList.toggle('aberto');
    botao.innerHTML = expandindo
        ? '<i class="fa-solid fa-chevron-down"></i> Mostrar menos'
        : '<i class="fa-solid fa-chevron-down"></i> Mostrar todas as comodidades';
}

function toggleImg() {
    const lista = document.querySelector('.areaImg');
    const botao = document.getElementById('botaoMostrarMaisImg');
    const extras = document.querySelectorAll('.areaImg .imagem.extra');
    const expandindo = !lista.classList.contains('expandida');

    if (expandindo) {
        // 1) Torna visível no layout (display: none -> inline-flex), ainda com opacity 0
        extras.forEach((item, i) => {
            item.classList.add('mostrando');
        });

        // 2) Força o navegador a "registrar" esse estado antes de animar
        void lista.offsetHeight;

        // 3) Só agora adiciona a classe que dispara a transição de opacity/transform
        lista.classList.add('expandida');

    } else {
        lista.classList.remove('expandida');
        //Espera a transição de saída terminar antes de sumir de vez (display: none)
        setTimeout(() => {
            extras.forEach(item => item.classList.remove('mostrando'));
        }, 100); // bate com o "0.35s" do CSS
    }

    botao.classList.toggle('aberto');
    botao.innerHTML = expandindo
        ? '<i class="fa-solid fa-chevron-down"></i> Mostrar menos'
        : '<i class="fa-solid fa-chevron-down"></i> Mostrar todas as imagens';
}