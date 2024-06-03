@extends('layouts.main')
@section('content')

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script src="{{ asset('js/newsCarousel.js') }}"></script>
    <script src="{{ asset('js/tariffsCarousel.js') }}"></script>

    <div class="banner">
        <div class="swiper news-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="text-swiper-slide">
                        <h2 class="text-swiper-slide-title">
                            НОВЫЙ СПОСОБ ОПЛАТЫ
                        </h2>
                        <p>
                            ОПЛАЧИВАЙТЕ ИНТЕРНЕТ УСЛУГИ <br> НЕ ВЫХОДЯ ИЗ ДОМА
                        </p>
                        <div class="more">
                            <span>ПОДРОБНЕЕ</span>
                            <span>></span>
                        </div>
                    </div>
                    <img src="./images/banner1.png" alt="Banner 1">
                    <a href="{{route('popolneniePSB')}}"> <span class="link"></span></a>
                </div>
                <div class="swiper-slide">
                    <div class="text-swiper-slide">
                        <h2 class="text-swiper-slide-title">
                            НОВЫЙ СПОСОБ ОПЛАТЫ
                        </h2>
                        <p>
                            ОПЛАЧИВАЙТЕ ИНТЕРНЕТ УСЛУГИ <br> НЕ ВЫХОДЯ ИЗ ДОМА
                        </p>
                        <div class="more">
                            <span>ПОДРОБНЕЕ</span>
                            <span>></span>
                        </div>
                    </div>
                    <img src="./images/banner1.png" alt="Banner 2">
                    <a href="{{route('popolneniePSB')}}"> <span class="link"></span></a>
                </div>
                <!-- Добавьте больше слайдов здесь -->
            </div>
            <!-- Добавьте элементы управления -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <div class="tariffs">
        <div class="tariff-title">
            ТАРИФЫ
        </div>
        <div class="tariff-type">
            <ul>
                <li class="tariff-type-item">
                    <a href="#">
                        ИНТЕРНЕТ И IPTV
                    </a>
                </li>
                <li class="tariff-type-item">
                    <a href="#">
                        ОБОРУДОВАНИЕ И УСЛУГИ
                    </a>
                </li>
            </ul>
        </div>
        <div class="swiper tariffs-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="swiper-slide-tariff">
                        <div class="text-swiper-slide">
                            <h3 class="text-swiper-slide-title">
                                "КЛАССИЧЕСКИЙ"
                            </h3>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/internet-speed-meter-lite-svgrepo-com.svg')}}"><span>20 Мбит/с</span>
                            </div>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/tv-mode-svgrepo-com.svg')}}"><span>300+ каналов</span>
                            </div>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/hd-svgrepo-com.svg')}}" width="20px"><span>HD качество</span>
                            </div>
                            <h3 class="text-swiper-slide-price">
                                200&#8381;
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="swiper-slide-tariff">
                        <div class="text-swiper-slide">
                            <h3 class="text-swiper-slide-title">
                                "СТИЛЬНЫЙ"
                            </h3>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/internet-speed-meter-lite-svgrepo-com.svg')}}"><span>60 Мбит/с</span>
                            </div>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/tv-mode-svgrepo-com.svg')}}"><span>300+ каналов</span>
                            </div>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/hd-svgrepo-com.svg')}}" width="20px"><span>HD качество</span>
                            </div>
                            <h3 class="text-swiper-slide-price">
                                250&#8381;
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="swiper-slide-tariff">
                        <div class="text-swiper-slide">
                            <h3 class="text-swiper-slide-title">
                                "ПРОРЫВ"
                            </h3>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/internet-speed-meter-lite-svgrepo-com.svg')}}"><span>100 Мбит/с</span>
                            </div>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/tv-mode-svgrepo-com.svg')}}"><span>300+ каналов</span>
                            </div>
                            <div class="text-swiper-slide-tariff-info">
                                <img src="{{asset('images/hd-svgrepo-com.svg')}}" width="20px"><span>HD качество</span>
                            </div>
                            <h3 class="text-swiper-slide-price">
                                350&#8381;
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <div class="pluses">
        <div class="plus-title">
            НАШИ ПРЕИМУЩЕСТВА
        </div>
        <div class="plus">
            <div class="plus-conteiner">
                <ul>
                    <il>
                        <div class="plus-content">
                            <img src="./images/term-of-work.svg">
                            <div class="plus-text">
                                <h3 class="plus-text-name">
                                    15 ЛЕТ НА РЫНКЕ
                                </h3>
                                <p>Большой опыт работы в данной сфере.<br>Компания остована в 2008 году</p>
                            </div>
                        </div>
                    </il>
                    <il>
                        <div class="plus-content">
                            <img src="./images/round-the-clock-support.svg">
                            <div class="plus-text">
                                <h3 class="plus-text-name">
                                    ПОДДЕРЖКА 24/7
                                </h3>
                                <p>Наши специалисты работают круглосуточно.<br>Они всегда готовы ответить на технические вопросы<br>и помочь в решении технических проблем.</p>
                            </div>
                        </div>
                    </il>
                    <il>
                        <div class="plus-content">
                            <img src="./images/deferred-payment.svg">
                            <div class="plus-text">
                                <h3 class="plus-text-name">
                                    ОТЛОЖЕННЫЙ ПЛАТЁЖ
                                </h3>
                                <p>Даём абонентам до 5 дней работы в кредит,<br>в случае отрицательного баланса.</p>
                            </div>
                        </div>
                    </il>
                </ul>
            </div>
            <div class="plus-conteiner">
                <ul>
                    <il>
                        <div class="plus-content">
                            <img src="./images/ruble.svg">
                            <div class="plus-text">
                                <h3 class="plus-text-name">
                                    КОНКУРЕНТНЫЕ ТАРИФЫ
                                </h3>
                                <p>Предоставляем абонентам разлычные тарифы,<br>которые соответсвуют их нуждам и требованиям</p>
                            </div>
                        </div>
                    </il>
                    <il>
                        <div class="plus-content">
                            <img src="./images/tv-svgrepo-com.svg">
                            <div class="plus-text">
                                <h3 class="plus-text-name">
                                    300+ КАНАЛОВ В HD КАЧЕСТВЕ
                                </h3>
                                <p>Все тарифы включают в себя <br>более трёхсот IPTV каналов в HD качестве</p>
                            </div>
                        </div>
                    </il>
                    <il>
                        <div class="plus-content">
                            <img src="./images/repair-mechanism-svgrepo-com.svg">
                            <div class="plus-text">
                                <h3 class="plus-text-name">
                                    ОБСЛУЖИВАНИЕ И РЕМОНТ
                                </h3>
                                <p>Поможем с обслуживанием и настройкой устройств,<br>а также с их ремонтом</p>
                            </div>
                        </div>
                    </il>
                </ul>
            </div>
        </div>
    </div>
@endsection
