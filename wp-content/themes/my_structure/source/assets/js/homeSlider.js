document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.logo-carousel').forEach((el) => {
        new Swiper(el, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 0, // Nessun ritardo tra le transizioni
                disableOnInteraction: false,
            },
            speed: 10000, // Rendi la velocità più lenta per un effetto continuo
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 1 },
                768: { slidesPerView: 1 },
                1024: { slidesPerView: 1 },
            }
        });
    });
});
