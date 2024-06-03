document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.news-swiper', {
        direction: 'horizontal',
        loop: true,
        speed: 800,
        pagination: {
            el: '.news-swiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.news-swiper .swiper-button-next',
            prevEl: '.news-swiper .swiper-button-prev',
        },
        autoplay: {
            delay: 10000,
            disableOnInteraction: false,
        },
    });
});
