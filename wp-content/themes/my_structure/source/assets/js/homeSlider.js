document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.logo-carousel').forEach((el) => {
        new Swiper(el, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 40000, // 4 secondi prima di passare alla prossima
                disableOnInteraction: false,
            },
            speed: 600, // velocità della transizione (in ms)
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
