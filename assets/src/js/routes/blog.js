import Swiper from "swiper";

export default {
    init() {
        const blogSliders = document.querySelectorAll('.blog-swiper');
        blogSliders && blogSliders.forEach(slider => {
           new Swiper(slider, {
              slidesPerView: 'auto',
              freeMode: true
           });
        });
    }
}