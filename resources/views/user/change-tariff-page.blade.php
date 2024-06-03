@extends('layouts.main')

@section('content')

    @php
        $user = Auth::user();
        $latestTariff = $user->getLatestTariff();
    @endphp

    <script>
        var csrfToken = "{{ csrf_token() }}";
        window.requestChangeTariffUrl = '{{ route('request.change.tariff') }}';

        document.addEventListener('DOMContentLoaded', function() {

            var continueButton = document.getElementById('modal-continue');
            var confirmationCodeDiv = document.getElementById('confirmation-code-div');
            var confirmCodeButton = document.getElementById('modal-confirm');

            continueButton.addEventListener('click', function() {

                var selectedRadio = document.querySelector('input[name="change-type"]:checked');
                if (!selectedRadio) {
                    alert('Выберите способ смены тарифа.');
                    return; // Прерываем выполнение функции, если радио кнопка не выбрана
                }

                fetch(window.requestChangeTariffUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                }).then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.message === 'Код подтверждения отправлен в Telegram.') {
                            confirmationCodeDiv.style.display = 'block';
                            continueButton.style.display = 'none';
                        }
                    }).catch(error => console.error('Error:', error));
            });

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

            document.querySelectorAll('.swiper-slide').forEach(function(slide) {
                slide.addEventListener('click', function() {
                    var tariffId = this.getAttribute('data-id');
                    var userBalance = {{ Auth::user()->balance }};
                    var newTariffPrice = parseFloat(this.querySelector('.text-swiper-slide-price').textContent.replace('₽', '').trim());
                    var currentTariffPrice = {{ $latestTariff->tariff->price }};

                    showModal(tariffId, newTariffPrice, currentTariffPrice, userBalance);
                });
            });

            function showModal(tariffId, newTariffPrice, currentTariffPrice, userBalance) {
                var modal = document.getElementById('modal');
                var closeModal = document.getElementById('modal-close');
                var confirmButton = document.getElementById('modal-confirm');

                document.getElementById('modal-tariff-id').value = tariffId;
                document.getElementById('modal-new-price').value = newTariffPrice;
                document.getElementById('modal-current-price').value = currentTariffPrice;

                closeModal.onclick = function() {
                    modal.style.display = 'none';
                };

                confirmCodeButton.onclick = function() {
                    var changeType = document.querySelector('input[name="change-type"]:checked').value;
                    var isUrgent = (changeType === 'urgent');
                    var servicePrice = isUrgent ? 100 : 50;  // Example service prices
                    var totalCost = newTariffPrice - currentTariffPrice + servicePrice;

                    if (userBalance >= totalCost) {
                        changeTariff(tariffId, isUrgent);
                    } else {
                        alert('Недостаточно средств на счету.');
                    }

                    modal.style.display = 'none';
                };

                modal.style.display = 'block';
            }

            function changeTariff(tariffId, isUrgent) {
                const code = document.getElementById('confirmation-code').value;
                fetch('/change-tariff', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        tariff_id: tariffId,
                        is_urgent: isUrgent,
                        code: code
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Тариф успешно изменён.');
                            location.reload();
                        } else {
                            alert('Ошибка при смене тарифа: ' + data.message);
                            confirmationCodeDiv.style.display = 'none';
                            continueButton.style.display = 'block';
                            document.getElementById('confirmation-code').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    </script>

    <div class="tariffs">
        <div class="tariff-title">
            Выберите тариф
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="swiper tariffs-swiper" id="tariffs-swiper">
                        <div class="swiper-wrapper">
                            @foreach($tariffs as $tariff)
                                <div class="swiper-slide" data-id="{{ $tariff->id }}">
                                    <div class="swiper-slide-tariff">
                                        <div class="text-swiper-slide">
                                            <h3 class="text-swiper-slide-title">
                                                "{{ $tariff->name }}"
                                            </h3>
                                            <div class="text-swiper-slide-tariff-info">
                                                <img src="{{ asset('images/internet-speed-meter-lite-svgrepo-com.svg') }}">
                                                <span>{{ $tariff->speed }} Мбит/с</span>
                                            </div>
                                            <div class="text-swiper-slide-tariff-info">
                                                <img src="{{ asset('images/tv-mode-svgrepo-com.svg') }}">
                                                <span>{{ $tariff->channels }}+ каналов</span>
                                            </div>
                                            <div class="text-swiper-slide-tariff-info">
                                                <img src="{{ asset('images/hd-svgrepo-com.svg') }}" width="20px">
                                                <span>HD качество</span>
                                            </div>
                                            <h3 class="text-swiper-slide-price">
                                                {{ $tariff->price }}₽
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span id="modal-close" class="modal-close">&times;</span>
            <h2>Смена тарифа</h2>
            <p>Выберите способ смены тарифа:</p>
            <input type="hidden" id="modal-tariff-id">
            <input type="hidden" id="modal-new-price">
            <input type="hidden" id="modal-current-price">
            <div class="change-type-option">
                <label for="next-month">Смена тарифа с первого числа следующего месяца (50₽)</label>
                <input type="radio" id="next-month" name="change-type" value="next_month">
            </div>
            <div class="change-type-option">
                <label for="urgent">Срочная смена тарифа (100₽)</label>
                <input type="radio" id="urgent" name="change-type" value="urgent">
            </div>
            <button id="modal-continue">Продолжить</button>
            <div id="confirmation-code-div" style="display:none;">
                <h2>Подтверждение смены тарифа</h2>
                <label for="confirmation-code">Введите код подтверждения:</label>
                <input type="text" id="confirmation-code" name="confirmation-code">
                <button id="modal-confirm">Подтвердить</button>
            </div>
        </div>
    </div>

@endsection
