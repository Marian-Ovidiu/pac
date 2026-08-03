import Swiper from 'swiper';
import { Pagination, A11y, Keyboard, Navigation } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.swiper-progetto').forEach((swiperEl) => {
        const slides = swiperEl.querySelectorAll('.swiper-slide');
        const shouldLoop = slides.length > 2;

        new Swiper(swiperEl, {
            modules: [Pagination, A11y, Keyboard, Navigation],
            slidesPerView: 1,
            spaceBetween: 0,
            loop: shouldLoop,
            autoplay: false,
            speed: 450,
            keyboard: {
                enabled: true,
                onlyInViewport: true,
                pageUpDown: true,
            },
            pagination: {
                el: swiperEl.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: swiperEl.querySelector('.swiper-button-next'),
                prevEl: swiperEl.querySelector('.swiper-button-prev'),
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
