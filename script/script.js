function goto(params) {
    window.location.href = params
}
function gotoAIRBNB() {
    window.location.href = 'https://media.discordapp.net/attachments/1521150818441760788/1551525975144136734/fcf4f3217fb9024a155c3a140cf88fde4ed5b76455ae522a39009e9fe849d4cf_1.png?ex=6ab63f2a&is=6ab4edaa&hm=994820e225770f2e852b66c7c1764fb4a3166e36db19eaeaa691458c945e2c1c&=&format=webp&quality=lossless&width=631&height=768'
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
        extras.forEach(item => {
            item.style.transitionDelay = '0s';
        });

        // Espera a transição de saída terminar antes de sumir de vez (display: none)
        setTimeout(() => {
            extras.forEach(item => item.classList.remove('mostrando'));
        }, 350); // bate com o "0.35s" do CSS
    }

    botao.classList.toggle('aberto');
    botao.innerHTML = expandindo
        ? '<i class="fa-solid fa-chevron-down"></i> Mostrar menos'
        : '<i class="fa-solid fa-chevron-down"></i> Mostrar todas as comodidades';
}