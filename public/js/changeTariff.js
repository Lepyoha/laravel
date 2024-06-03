document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('#tariffs-swiper', {
        direction: 'horizontal',
        loop: false,
        watchOverflow: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
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

// Обработчик события клика по слайду
    $('.swiper-slide').on('click', function () {
        var tariffId = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/change-tariff',
            data: {
                tariff_id: tariffId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                // В случае успеха обновляем страницу или как-то иначе обрабатываем результат
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
});
