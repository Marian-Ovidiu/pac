import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-home-hero-slider').forEach((el) => {
        const slides = el.querySelectorAll('.swiper-slide');
        if (!slides.length) {
            return;
        }

        const multi = slides.length > 1;

        new Swiper(el, {
            modules: [Autoplay, Pagination],
            slidesPerView: 1,
            spaceBetween: 16,
            loop: multi,
            speed: 700,
            grabCursor: true,
            watchOverflow: true,
            autoplay: multi
                ? {
                      delay: 5200,
                      disableOnInteraction: false,
                      pauseOnMouseEnter: true,
                  }
                : false,
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },
        });
    });
});
