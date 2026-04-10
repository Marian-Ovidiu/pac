document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-home-hero-slider').forEach((el) => {
        new Swiper(el, {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: el.querySelectorAll('.swiper-slide').length > 1,
            speed: 700,
            autoHeight: true,
            grabCursor: true,
            watchOverflow: true,
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },
        });
    });
});
