document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.tariffs-swiper', {
        direction: 'horizontal',
        loop: false,
        speed: 800,
        slidesPerView: 3,
        watchOverflow: true,
        navigation: {
            nextEl: '.tariffs-swiper .swiper-button-next',
            prevEl: '.tariffs-swiper .swiper-button-prev',
        },
        breakpoints: {
            200: {
                slidesPerView: 1,
            },
            760: {
                slidesPerView: 2,
            },
            1380: {
                slidesPerView: 3,
            }
        }
    });
});
