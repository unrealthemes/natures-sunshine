export default function cardHeightResize() {
  const cards = document.querySelectorAll('.card:not(.favorites-card):not(.included-card)');
  const heights = [];

  for (let i = 0; i < cards.length; i++) {
    heights.push(cards[i].offsetHeight);
  }

  const getHighest = () => Math.max(...heights);

  cards && cards.forEach(card => {
    if (window.innerWidth >= 1200) {
      card.parentElement.style.height = getHighest() + 'px';
    } else {
      const collapse = card.querySelector('.card__collapse');

      card.parentElement.style.removeProperty('height');
      card.classList.remove('hovered');

      if (collapse && collapse.style.maxHeight) {
        collapse.style.maxHeight = null;
      }
    }
  });
}