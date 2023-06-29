export default function () {
  const bp768 = window.matchMedia('(min-width: 768px)');
  const bp1200 = window.matchMedia('(min-width: 1200px)');
  const cardsList = document.querySelector('.cards');
  const cards = document.querySelectorAll('.card:not(.favorites-card)');
  // const cardIcons = document.getElementsByClassName('card__icon');
  const catalogViewButtons = document.querySelectorAll('.catalog-sort__view-btn');
  const expression = /(Mac|iPhone|iPod|iPad)/i;

  /* Cards list & switch buttons clear */
  const switchDesktopCardsView = () => {
    catalogViewButtons.forEach(btn => {
      btn.classList.remove('active');
      btn.dataset.view === 'grid' && btn.classList.add('active');
    });
    cardsList.classList.contains('cards--list') && cardsList.classList.remove('cards--list');
  }

  /* Mobile cards list switch view */
  const switchMobileCardsView = () => {
    catalogViewButtons.forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();

        for (let view of catalogViewButtons) {
          view.classList.remove('active');
        }

        e.currentTarget.classList.add('active');

        if (e.currentTarget.dataset.view === 'list') {
          !cardsList.classList.contains('cards--list') && cardsList.classList.add('cards--list');
        } else {
          cardsList.classList.contains('cards--list') && cardsList.classList.remove('cards--list');
        }

        cardsList.classList.add('loading');
        setTimeout(() => {
          cardsList.classList.remove('loading');
        }, 1000);
      });
    });
  }

  const handleCardsListView = bp => {
    if (bp.matches) {
      switchDesktopCardsView();
    } else {
      switchMobileCardsView();
    }
  }

  if (expression.test(navigator)) {
    cardsList && bp1200.addEventListener('change', handleCardsListView);
  } else {
    bp1200.onchange = cardsList && handleCardsListView;
  }
  cardsList && handleCardsListView(bp1200);

  /* Desktop card hover */
  const hoverCard = e => {
    let target = e || e.target;
    const collapse = target.querySelector('.card__collapse');

    target.classList.add('hovered');
    target.parentElement.style.zIndex = 2;

    if (collapse && !collapse.style.maxHeight) {
      collapse.style.maxHeight = collapse.scrollHeight + "px";
    }
  }

  /* Desktop card unhover */
  const unHoverCard = e => {
    let target = e || e.target;
    const collapse = target.querySelector('.card__collapse');

    target.classList.remove('hovered');
    target.parentElement.style.zIndex = 'unset';

    if (collapse && collapse.style.maxHeight) {
      collapse.style.maxHeight = null;
    }
  }

  // [...cards].forEach(card => {
  //     card.addEventListener('mouseenter', hoverCard, false);
  //     card.addEventListener('mouseleave', unHoverCard, false);
  // });

  // const handleDesktopCardsHover = bp => {
  //     ['mouseover', 'mouseout'].forEach(event => {
  //         document.addEventListener(event, e => {
  //             document.querySelectorAll('.card:not(.favorites-card)').forEach(card => {
  //                 if (bp.matches) {
  //                     if (card.contains(e.target)) {
  //                         'mouseover' === event && hoverCard(card);
  //                         'mouseout' === event && unHoverCard(card);
  //                     }
  //                 } else {
  //                     card.removeEventListener('mouseenter', hoverCard);
  //                     card.removeEventListener('mouseleave', unHoverCard);
  //                 }
  //             });
  //         });
  //     });
  // }

  const handleDesktopCardsHover = () => {
    ['mouseover', 'mouseout'].forEach(event => {
      document.addEventListener(event, e => {
        document.querySelectorAll('.card:not(.favorites-card)').forEach(card => {
          if (card.contains(e.target)) {
            'mouseover' === event && hoverCard(card);
            'mouseout' === event && unHoverCard(card);
          }
        });
      });
    });
  }

  handleDesktopCardsHover();

  // if (expression.test(navigator)) {
  //     cards && bp1200.addEventListener('change', handleDesktopCardsHover);
  // } else {
  //     bp1200.onchange = cards && handleDesktopCardsHover;
  // }
  // cards && handleDesktopCardsHover(bp1200);

  /* Cards slide's height */
  // const cardHeightResize = () => {
  //     document.querySelectorAll('.card:not(.favorites-card)').forEach(card => {
  //         if (window.innerWidth >= 1200) {
  //             card.parentElement.style.height = card.scrollHeight + 'px';
  //         } else {
  //             const collapse = card.querySelector('.card__collapse');
  //
  //             card.parentElement.style.removeProperty('height');
  //             card.classList.remove('hovered');
  //
  //             if (collapse && collapse.style.maxHeight) {
  //                 collapse.style.maxHeight = null;
  //             }
  //         }
  //     });
  // }

  ['load', 'resize'].forEach(event => {
    window.addEventListener(event, () => {
      cardsList && !cardsList.classList.contains('cards--list') && cardHeightResize();
      // cardIcons && tooltipShowPosition();
    });
  });

  /* Cards list view */
  const cardElementsMigrate = bp => {
    if (cardsList && cardsList.classList.contains('cards--list')) {
      [...cards].forEach(card => {
        const cardInfo = card.querySelector('.card__info');
        const cardType = card.querySelector('.card__type');
        const cardTitle = card.querySelector('.card__title');

        if (bp.matches) {
          !cardInfo.contains(cardTitle) && cardInfo.prepend(cardTitle);
          !cardInfo.contains(cardType) && cardInfo.prepend(cardType);
        } else {
          cardInfo.contains(cardTitle) && card.prepend(cardTitle);
          cardInfo.contains(cardType) && card.prepend(cardType);
        }
      });
    }
  }

  if (expression.test(navigator)) {
    cards && bp768.addEventListener('change', cardElementsMigrate);
  } else {
    bp768.onchange = cards && cardElementsMigrate;
  }
  cards && cardElementsMigrate(bp768);
}