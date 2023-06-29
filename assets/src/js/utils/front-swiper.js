import Swiper, {Navigation, Pagination} from "swiper";

export default function () {
    /* Hero */
    const frontSlider = document.querySelector('.front-carousel');

    frontSlider && new Swiper(frontSlider, {
        modules: [Navigation, Pagination],
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: '.front-carousel__pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.front-carousel__next',
            prevEl: '.front-carousel__prev',
        }
    });

    /* Themes */
    const rowSlider = document.querySelectorAll('.row-slider');

    const sliderPosition = (slider, start, end) => {
        if (start) {
            slider.classList.remove('middle', 'end');
            slider.classList.add('start');
        } else if (end) {
            slider.classList.remove('middle', 'start');
            slider.classList.add('end');
        } else {
            slider.classList.remove('start', 'end');
            slider.classList.add('middle');
        }
    }

    rowSlider && rowSlider.forEach(slider => {
        new Swiper(slider, {
            modules: [Navigation],
            slidesPerView: 'auto',
            freeMode: true,
            spaceBetween: 10,
            breakpoints: {
                768: {
                    spaceBetween: 20
                }
            },
            navigation: {
                nextEl: '.row-slider__next',
                prevEl: '.row-slider__prev',
            },
            on: {
                init: swiper => {
                    swiper.isBeginning && slider.classList.add('start');
                },
                transitionEnd: swiper => {
                    sliderPosition(slider, swiper.isBeginning, swiper.isEnd)
                },
                resize: swiper => {
                    window.innerWidth < 992
                        ? slider.classList.remove('start', 'middle', 'end')
                        : sliderPosition(slider, swiper.isBeginning, swiper.isEnd)
                }
            }
        });
    });
}