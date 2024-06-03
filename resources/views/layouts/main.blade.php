<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNET</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/dropdownMenu.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</head>
<body>
<header>
    <div class="container">
        <div class="header">
            <a href="{{ route('welcome') }}"><img class="logo" src="{{asset('images/vnet-logo-mono.svg')}}" alt="VNET Logo"></a>
            <nav class="header_nav">
                <ul>
                    <li class="header_nav_item">
                        <a href="#">ИНТЕРНЕТ И IPTV</a>
                    </li>
                    <li class="header_nav_item">
                        <a href="#">ОБОРУДОВАНИЕ И УСЛУГИ</a>
                    </li>
                    <li class="header_nav_item">
                        <a href="#">НАСТРОЙКИ</a>
                    </li>
                    <li class="header_nav_item">
                        <a href="#">ОПЛАТА</a>
                    </li>
                    <div class="drop_dots_menu">
                        <button onclick="SubMenu('threeDots')" class="dropbtn1">
                            &#8942;
                        </button>
                        <div id="threeDots" class="dropdown_dots_content">
                            <a href="#">НОВОСТИ</a>
                            <a href="#">ОПЛАТА</a>
                            <a href="#">НОВОСТИ</a>
                            <a href="#">КОНТАКТЫ</a>
                            <a href="#">РАБОТА В VNET</a>
                            <a href="#">О НАС</a>
                        </div>
                    </div>
                    <div class="drop_communication_menu">
                        <button onclick="SubMenu('communication')" class="dropbtn2">
                            <img class="communication-images" src="{{asset('images/communication-bubble-chat-comments-conversation-message-icon-svgrepo-com.svg')}}">
                            <span>Связаться с нами</span>
                        </button>
                        <div id="communication" class="dropdown_communication_content">
                            <a href="tel:+7(949)333-31-12">+7(949)333-31-12</a>
                            <a href="https://t.me/Office_Vnet_manager" target="_blank">Telegram</a>
                            <a href="https://t.me/VNet_chat_bot" target="_blank">Telegram Bot</a>
                        </div>
                    </div>

                    @if (Route::has('login'))
                            @auth

                            <button onclick="redirectToAccount()" class="personalАccountBtn">
                                <span>ЛИЧНЫЙ КАБИНЕТ</span>
                            </button>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="logoutBtn">
{{--                                    <img class="logout-images" src="./images/logout-svgrepo-com.svg">--}}
                                    <span>ВЫЙТИ</span>
                                </button>
                            </form>
                            @else

                            <button onclick="redirectToLogin()" class="authorizationBtn">
                                <img class="login-images" src="{{asset('images/login-svgrepo-com.svg')}}">
                                <span>ВОЙТИ</span>
                            </button>
                            @endauth
                    @endif

                    <div class="drop_menu_menu">
                        <button onclick="SubMenu('menu')" class="dropbtn4">
                            <img class="menu-images" src="{{asset('images/menu-burger-svgrepo-com.svg')}}">
                            <span>МЕНЮ</span>
                            <div id="menu" class="dropdown_menu_content">
                                <a href="#">ИНТЕРНЕТ И IPTV</a>
                                <a href="#">ОБОРУДОВАНИЕ И УСЛУГИ</a>
                                <a href="#">НАСТРОЙКИ</a>
                                <a href="#">ОПЛАТА</a>
                                <a href="#">НОВОСТИ</a>
                                <a href="#">КОНТАКТЫ</a>
                                <a href="#">РАБОТА В VNET</a>
                                <a href="#">О НАС</a>
                            </div>
                        </button>
                    </div>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main>
    @yield('content')
</main>
<footer>
    <div class="container">
        <div class="footer">
            <div class="footer-img-and-copyright">
                <a href="https://t.me/Office_Vnet_manager" target="_blank">
                    <img src="{{asset('images/telegram-svgrepo-com.svg')}}">
                </a>
                <a href="https://t.me/VNet_chat_bot" target="_blank">
                    <img src="{{asset('images/robot-outline-in-a-circle-svgrepo-com.svg')}}">
                </a>
                <p>&#169; {{ date('Y') }}</p>
            </div>
            <div class="footer-info">
                <h3>ОФИС ОБСЛУЖИВАНИЯ</h3>
                <a href="https://yandex.ru/maps/-/CCU9NVqMWC">г. Донецк, Ляшенко 8</a>
                <p>Режим работы: с 9:00 до 18:00, БЕЗ перерыва и выходных</p>
            </div>
        </div>
    </div>
</footer>
<script>

    function redirectToAccount() {
        // Получаем тип пользователя
        var userType = "{{ Auth::check() ? Auth::user()->userType->name : '' }}";

        // Определяем маршрут на основе типа пользователя
        switch (userType) {
            case 'Администратор':
                window.location.href = "{{ route('admin.home') }}";
                break;
            case 'Менеджер технической поддержки':
                window.location.href = "{{ route('support.home') }}";
                break;
            default:
                window.location.href = "{{ route('home') }}";
                break;
        }
    }

    function redirectToLogin() {
        window.location.href = "{{ route('login') }}";
    }

</script>
</body>
</html>
