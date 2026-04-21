import Swiper from 'swiper';
import { Autoplay, Pagination, A11y, Keyboard } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.swiper-progetto').forEach((swiperEl) => {
        const slides = swiperEl.querySelectorAll('.swiper-slide');
        const shouldLoop = slides.length > 2;

        new Swiper(swiperEl, {
            modules: [Autoplay, Pagination, A11y, Keyboard],
            slidesPerView: 1,
            spaceBetween: 0,
            loop: shouldLoop,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            speed: 600,
            keyboard: {
                enabled: true,
                onlyInViewport: true,
                pageUpDown: true,
            },
            pagination: {
                el: swiperEl.querySelector('.swiper-pagination'),
                clickable: true,
            },
            a11y: {
                enabled: true,
                prevSlideMessage: 'Immagine precedente',
                nextSlideMessage: 'Immagine successiva',
                paginationBulletMessage: "Vai all'immagine {{index}}",
            },
            breakpoints: {
                640: { slidesPerView: 1 },
                768: { slidesPerView: 1 },
                1024: { slidesPerView: 1 },
            },
        });
    });
});
