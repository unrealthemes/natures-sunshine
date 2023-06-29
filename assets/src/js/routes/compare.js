export default {
    init() {
        const compare = document.querySelector('section.compare');
        const compareSwiper = document.querySelectorAll('.compare-swiper')[0];
        const compareSticky = document.querySelector('.compare-sticky');
        const compareCards = compareSwiper && compareSwiper.querySelectorAll('.compare-card');
        const compareCardsPreview = document.querySelectorAll('.compare-card .card');
        const compareBarAlert = document.querySelector('.compare-bar__alert');
        const comparePrev = document.querySelector('.compare-swiper .cards__prev');
        const compareNext = document.querySelector('.compare-swiper .cards__next');
        const compareSpecsSlider = document.querySelectorAll('.compare-specs__slider');

        if (compareSwiper.swiper.slides.length < 2) {
            compareBarAlert.classList.add('shown');
        } else {
            compareBarAlert.classList.remove('shown');
        }

        console.log(compareCards)

        if (compareCards && compareCards.length >= 4) {
            compare.classList.add('full-width');
            compareCards.forEach(card => card.classList.add('compare-card--4'));
        } else {
            compare.classList.remove('full-width');
            compareCards.forEach(card => card.classList.remove('compare-card--4'));
        }

        window.addEventListener('scroll', () => {
            let header = document.querySelector('header');
            let stickyTop = compareSticky.getBoundingClientRect().top;
            let headerRect = header.getBoundingClientRect();
            let headerHeight = headerRect.height;

            // compareSticky.style.height = compareSticky.offsetHeight + 'px';

            if (stickyTop <= 0 && window.innerWidth >= 992) {
                compareSticky.classList.add('sticky');
                // compareSticky.style.top = headerHeight + 'px';
                compareCardsPreview.forEach(preview => preview.classList.add('fold'));
                setTimeout(() => {
                    comparePrev.style.top = '20px';
                    compareNext.style.top = '20px';
                }, 200);
            } else {
                compareSticky.classList.remove('sticky');
                // compareSticky.style.top = null;
                compareCardsPreview.forEach(preview => preview.classList.remove('fold'));
                setTimeout(() => {
                    let preview = compareSwiper.querySelectorAll('.card__preview')[0];
                    let top = preview && preview.scrollHeight / 2;
                    comparePrev.style.top = top + 'px';
                    compareNext.style.top = top + 'px';
                }, 200);
            }
        });
    }
}