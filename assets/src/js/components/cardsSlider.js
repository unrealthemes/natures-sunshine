import Swiper, {Navigation} from "swiper";

export default function cardsSliderInit() {
  /* Cards */
  const cards = document.querySelectorAll('.cards:not(.catalog-cards)');

  const navigationTop = (prev, next, top) => {
    prev.style.top = top + 'px';
    next.style.top = top + 'px';
  }

  const cardsSettings = (swiper) => {
    let prev = swiper.navigation.prevEl;
    let next = swiper.navigation.nextEl;
    let preview = swiper.el.querySelectorAll('.card__preview')[0];
    let top = preview && preview.scrollHeight / 2;
    top && navigationTop(prev, next, top);
  }

  cards && cards.forEach(card => {
    let compare = card.classList.contains('compare-swiper');
    let specs = card.classList.contains('compare-specs__slider');
    new Swiper(card, {
      modules: [Navigation],
      slidesPerView: 'auto',
      spaceBetween: !compare && 10,
      watchSlidesProgress: true,
      allowTouchMove: !specs,
      navigation: {
        nextEl: '.cards__next',
        prevEl: '.cards__prev',
      },
      breakpoints: {
        768: {
          spaceBetween: !compare && 20
        }
      },
      on: {
        init: cardsSettings,
        resize: cardsSettings,
        slideChange: function (swiper) {
          if (compare) {
            const specsSliders = document.querySelectorAll('.compare-specs__slider');
            specsSliders && specsSliders.forEach(spec => {
              swiper.swipeDirection === 'prev' && spec.swiper.slidePrev();
              swiper.swipeDirection === 'next' && spec.swiper.slideNext();
            });
          }
        }
      }
    });
  });
}