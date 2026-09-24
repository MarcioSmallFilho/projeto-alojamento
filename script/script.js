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