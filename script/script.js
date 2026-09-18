function goto(params) {
    window.location.href = params
}
function gotoAIRBNB() {
    window.location.href = 'https://airbnb.com'
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