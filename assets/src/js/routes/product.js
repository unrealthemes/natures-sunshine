import Swiper, {Pagination, EffectFade, Thumbs} from "swiper";
import inlineScroll from "../utils/inlineScroll";
import tabs from "../utils/tabs";
import accordion from "../utils/accordion";
import {Panzoom} from "@fancyapps/ui";

export default {
    init() {
        /* Product gallery */
        const bp576 = window.matchMedia('(max-width: 575.98px)');
        const bp1200 = window.matchMedia('(min-width: 1200px)');
        const expression = /(Mac|iPhone|iPod|iPad)/i;
        const productThumbs = document.querySelector('.product-thumbs');
        const productThumbsSlides = productThumbs && productThumbs.querySelectorAll('.swiper-slide');
        const productSlider = document.querySelector('.product-slider');
        let productSliderSwiper;
        let productThumbsSwiper;

        const enableProductSlider = function() {
            productThumbsSwiper = productThumbs && new Swiper(productThumbs, {
                direction: 'vertical',
                slidesPerView: 5,
                freeMode: true,
                spaceBetween: 20,
                watchSlidesProgress: true
            });

            productSliderSwiper = new Swiper(productSlider, {
                modules: [EffectFade, Pagination, Thumbs],
                slidesPerView: 1,
                spaceBetween: 20,
                effect: 'fade',
                allowTouchMove: true,
                fadeEffect: {
                    crossFade: true
                },
                thumbs: {
                    swiper: productThumbsSwiper,
                },
                breakpoints: {
                    1200: {
                        allowTouchMove: false,
                    }
                }
            });
        }

        const thumbsResize = thumbs => {
            productThumbs.style.height = productSlider.scrollHeight + 'px';

            thumbs.forEach(thumb => {
                const w = thumb.scrollWidth;
                thumb.style.height = w + 'px';
                thumb.style.paddingTop = w + 'px';
            });
        }

        const productSliderInit = (bp1, bp2) => {
            if (bp1.matches || bp2.matches) {
                productSlider.classList.remove('inline-scroll');
                return enableProductSlider();
            } else {
                productSliderSwiper !== undefined && productSliderSwiper.destroy( true, true );
                productThumbsSwiper !== undefined && productThumbsSwiper.destroy( true, true );
                productSlider.classList.add('inline-scroll');
                inlineScroll();
            }
        }

        if (expression.test(window.navigator)) {
            productSlider && bp1200.addEventListener('change', () => productSliderInit(bp576, bp1200));
            productSlider && bp576.addEventListener('change', () => productSliderInit(bp576, bp1200));
        } else {
            bp1200.onchange = productSlider && productSliderInit(bp576, bp1200);
            bp576.onchange = productSlider && productSliderInit(bp576, bp1200);
        }
        productSlider && productSliderInit(bp576, bp1200);

        productThumbs && thumbsResize(productThumbsSlides);
        window.addEventListener('resize', () => {
            productThumbs && thumbsResize(productThumbsSlides);

            if (window.innerWidth < 768) {
                tabsCollapse && tabsCollapse.forEach(collapse => {
                    if (collapse.classList.contains('active')) {
                        if (collapse.style.maxHeight) {
                            collapse.style.maxHeight = null;
                        } else {
                            collapse.style.maxHeight = collapse.scrollHeight + "px";
                        }
                    }
                });
            }
        });

        /* Description */
        const bp768 = window.matchMedia('(min-width: 768px)');
        const descLinks = document.querySelectorAll('.tabs-link');
        const descPanels = document.querySelectorAll('.tabs-panel');
        const tabsTitle = document.querySelectorAll('.tabs-panel__title');
        const tabsCollapse = document.querySelectorAll('.tabs-panel__collapse');

        const removeAccordionStyles = () => {
            for (let i = 0; i < tabsTitle.length; i++) {
                tabsTitle[i].classList.contains('active') && tabsTitle[i].classList.remove('active');
            }
            for (let i = 0; i < tabsCollapse.length; i++) {
                tabsCollapse[i].hasAttribute('style') && tabsCollapse[i].removeAttribute('style');
            }
        }

        const handleTabs = e => tabs(e, descLinks, descPanels);

        const handleAccordion = e => accordion(e, tabsTitle, tabsCollapse);

        const productAccordionTabs = (bp) => {
            if (bp.matches) {
                removeAccordionStyles();
                tabsTitle.forEach(title => {
                    title.removeEventListener('click', handleAccordion);
                });
                descLinks.forEach(link => {
                    link.addEventListener('click', handleTabs);
                });
            } else {
                descLinks.forEach(link => {
                    link.removeEventListener('click', handleTabs);
                });
                tabsTitle.forEach(title => {
                    title.addEventListener('click', handleAccordion);
                });
            }
        }

        if (expression.test(window.navigator)) {
            descLinks && bp768.addEventListener('change', productAccordionTabs);
        } else {
            bp768.onchange = descLinks && productAccordionTabs;
        }
        descLinks && productAccordionTabs(bp768);

        /* Description panel */
        const header = document.querySelector('header.header');
        const description = document.querySelector('.description');
        const tabsElement = document.querySelector('.description .tabs');
        const productPanel = document.querySelector('.product-panel');

        tabsElement && window.addEventListener('scroll', () => {
            if (window.innerWidth >= 992) {
                const headerHeight = header.clientHeight;
                let headerOffset = header.getBoundingClientRect().top;
                const tabsOffset = tabsElement.offsetTop + tabsElement.scrollHeight;
                const descriptionOffset = description.offsetTop + description.scrollHeight - (productPanel && productPanel.scrollHeight);

                if (window.scrollY >= tabsOffset && window.scrollY <= descriptionOffset) {
                    if (productPanel) {
                        productPanel.classList.add('active');
                        productPanel.style.top = 0 + 'px';
                    }
                } else {
                    if (productPanel) {
                        productPanel && productPanel.classList.remove('active');
                        productPanel && productPanel.removeAttribute('style');
                    }
                }
            }
        });

        /* Zoom */
        const psci = document.querySelectorAll('.product-slider__card-image');

        psci && psci.forEach(image => {
            window.innerWidth >= 1200 && new Panzoom(image, {
                wheel: false
            });
        });
    }
}