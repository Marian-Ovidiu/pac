
document.addEventListener('DOMContentLoaded', function () {
    var verticalSwiper = new Swiper(".vertical-slide-carousel", {
        loop: true,
        direction: 'vertical',
        mousewheel: {
            releaseOnEdges: true,
        },
        grabCursor: true,
        pagination: {
            el: ".vertical-slide-carousel .swiper-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 5500,
            disableOnInteraction: false,
        },

    });

    var logoSwiper = new Swiper(".logo-carousel", {
        slidesPerView: 3,
        loop: true,
        speed: 8000,
        autoplay: {
            delay: 0,
            disableOnInteraction: false,
        },
        grabCursor: false,
    });
});
