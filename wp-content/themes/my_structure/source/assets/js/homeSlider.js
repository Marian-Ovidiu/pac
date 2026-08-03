import Swiper from 'swiper';
import { A11y, Keyboard, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-home-hero-slider').forEach((el) => {
        const slides = el.querySelectorAll('.swiper-slide');
        if (!slides.length) {
            return;
        }

        const multi = slides.length > 1;

        new Swiper(el, {
            modules: [A11y, Keyboard, Navigation, Pagination],
            slidesPerView: 1,
            spaceBetween: 16,
            loop: false,
            speed: 450,
            grabCursor: true,
            watchOverflow: true,
            autoplay: false,
            keyboard: { enabled: true, onlyInViewport: true },
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: multi ? {
                nextEl: el.querySelector('.swiper-button-next'),
                prevEl: el.querySelector('.swiper-button-prev'),
            } : false,
            a11y: {
                enabled: true,
                prevSlideMessage: 'Contenuto precedente',
                nextSlideMessage: 'Contenuto successivo',
                paginationBulletMessage: 'Vai al contenuto {{index}}',
            },
        });
    });
});
