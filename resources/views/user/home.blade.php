@extends('layouts.main')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">


    @php
        $telegramVerification = \App\Models\TelegramVerification::where('id_user', Auth::user()->id)->first();

        $user = Auth::user();
        $latestTariff = $user->getLatestTariff();
    @endphp

    <script>
        var csrfToken = "{{ csrf_token() }}";
        var addTelegramIDUrl = "{{ route('addTelegramID') }}";
        var unlinkTelegramIDUrl = "{{ route('unlinkTelegramID') }}";
        var sendTelegramMessageUrl = "{{ route('sendTelegramMessage') }}";
        var updateBalanceUrl = "{{ route('updateBalance') }}";
        window.requestDelayedPaymentUrl = '{{ route('request.delayed.payment') }}';
        window.confirmDelayedPaymentUrl = '{{ route('confirm.delayed.payment') }}';
    </script>

    <script src="{{ asset('js/delayed_payment.js') }}"></script>
    <script src="{{ asset('js/telegramIntegration.js') }}"></script>
    <script src="{{ asset('js/balance.js') }}"></script>
    <script src="{{ asset('js/freezeAccount.js') }}"></script>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="user-info">
                            <ul class="user-info-list">
                                <li class="user-info-item"><strong class="user-info-label">{{ __('Статус:') }}</strong> <span class="user-info-value">{{ \App\Enums\UserStatus::getDescription(Auth::user()->status) }}</span><button id="freeze-account-button" class="btn btn-primary">Заморозка</button></li>

                                <!-- Freeze Account Modal -->
                                <div id="freezeAccountModal" class="modal">
                                    <div class="modal-content">
                                        <span id="modal-close" class="modal-close">&times;</span>
                                        @csrf
                                        <div class="freezing-dates">
                                            <h2>Заморозка аккаунта</h2>
                                            <label for="end_date">Дата окончания заморозки:</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Тип заморозки:</label>
                                            <div>
                                                <input type="radio" id="urgent" name="freeze_type" value="urgent" required>
                                                <label for="urgent">Срочная заморозка</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="next_month" name="freeze_type" value="next_month" required>
                                                <label for="next_month">Заморозка с первого числа месяца</label>
                                            </div>
                                        </div>
                                        <button id="freeze-request-button" class="btn btn-primary">Отправить запрос</button>
                                        @csrf
                                        <div id="confirm-freezing-dates" style="display:none;">
                                            <h2>Подтверждение заморозки аккаунта</h2>
                                            <label for="confirmation_code">Код подтверждения:</label>
                                            <input type="text" id="confirmation_code" name="confirmation_code" class="form-control" required>
                                            <input type="hidden" id="end_date_hidden" name="end_date">
                                            <input type="hidden" id="freeze_type_hidden" name="freeze_type">
                                            <button id="confirm-freeze-button" class="btn btn-primary">Подтвердить заморозку</button>
                                        </div>
                                    </div>
                                </div>

                                <li class="user-info-item"><strong class="user-info-label">{{ __('Имя пользователя:') }}</strong> <span class="user-info-value">{{ Auth::user()->username }}</span></li>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('Баланс:') }}</strong> <span class="user-info-value">{{ Auth::user()->balance }} руб</span>
                                @if ($canRequestDelayedPayment)

                                    @if($telegramVerification)
                                            @if($telegramVerification->telegram_id != null && $telegramVerification->verification != 0)
                                                <button id="request-delayed-payment-button">Отложенный платёж</button>
                                </li>
                                            @else
                                            <li class="user-info-item"><span class="user-info-value" style="color: red">Подтвердите привязку Telegram для оформления отложенного платежа.</span>
                                            @endif
                                    @else
                                        <li class="user-info-item"><span class="user-info-value" style="color: red">Привяжите Telegram для оформления отложенного платежа.</span></li>
                                    @endif

                                @else
                                    @if(Auth::user()->balance < -Auth::user()->credit)
                                        <li class="user-info-item" style="color: red"><span class="user-info-value">Отложенный платёж недоступен.</span></li>
                                    @else
                                        @if(Auth::user()->last_delayed_payment != null)
                                            <li class="user-info-item"><strong class="user-info-label">{{ __('Кредит:') }}</strong>
                                                <span class="user-info-value">
                                                    {{ Auth::user()->credit }}
                                                    @if($creditExpiryDate)
                                                        руб до {{ $creditExpiryDate->format('d.m.Y') }}
                                                    @endif
                                                    </span>
                                            </li>
                                        @endif
                                    @endif
                                @endif

                                <div id="confirmation-section" style="display: none;">
                                    <input type="text" id="confirmation-code" placeholder="Введите код подтверждения">
                                    <button id="confirm-delayed-payment-button">Подтвердить платёж</button>
                                </div>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('Почта:') }}</strong> <span class="user-info-value">{{ Auth::user()->email }}</span></li>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('ФИО:') }}</strong> <span class="user-info-value">{{ Auth::user()->full_name }}</span></li>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('Адрес:') }}</strong> <span class="user-info-value">{{ \App\Enums\UserCity::getDescription(Auth::user()->city) }}, ул. {{ Auth::user()->street }}, д. {{ Auth::user()->house_number }}, кв. {{ Auth::user()->apartment_number }}, этаж {{ Auth::user()->floor }}</span></li>

                                <li class="user-info-item">
                                    <strong class="user-info-label">Telegram ID:</strong>

                                    @if($telegramVerification)
                                        @if($telegramVerification->verification)
                                            <span class="user-info-value">{{ $telegramVerification->telegram_id }}</span>
                                            <button onclick="unlinkTelegram()">Отвязать</button>
                                        @else
                                            <span class="user-info-value" style="color: red">Ждёт подтверждения в боте Telegram</span>
                                            <button onclick="changeTelegramID()">Сменить ID</button>
                                            <button onclick="telegramBot()">Открыть бота</button>
                                        @endif
                                    @else
                                        <span class="user-info-value" style="color: red">Не привязан</span>
                                            <button onclick="linkTelegram()">Привязать</button>
                                <li class="user-info-item" id="telegram-instruction" style="display: none;">
                                    <strong class="user-info-label">Инструкция по привязке:</strong>
                                    <span class="user-info-value">Пожалуйста, введите Ваш Telegram ID в поле ниже и нажмите "Привязать".</span>
                                    <input type="text" id="telegram-id-input">
                                    <button onclick="addTelegramID()">Привязать</button>
                                    <button onclick="telegramBot()">Узнать ID</button>
                                </li>
                                @endif


                                @php
                                    $primaryPhoneNumber = Auth::user()->phoneNumbers()->wherePivot('is_primary', true)->first();
                                    $otherPhoneNumbers = Auth::user()->phoneNumbers()->wherePivot('is_primary', false)->get();
                                @endphp

                                @if($primaryPhoneNumber)
                                    <li class="user-info-item"><strong class="user-info-label">{{ __('Основной номер телефона:') }}</strong> <span class="user-info-value">{{ $primaryPhoneNumber->number }}</span></li>
                                @endif

                                @if($otherPhoneNumbers->isNotEmpty())
                                    <li class="user-info-item"><strong class="user-info-label">{{ __('Другие номера телефонов:') }}</strong> <span class="user-info-value">
                                            @foreach($otherPhoneNumbers as $phoneNumber)
                                                {{ $phoneNumber->number }}@if (!$loop->last), @endif
                                            @endforeach</span></li>
                                @endif

                                <li class="user-info-item"><strong class="user-info-label">{{ __('Тариф:') }}</strong>
                                    <span class="user-info-value">
                                        {{ $latestTariff ? $latestTariff->tariff->name : 'Нет тарифа' }}
                                    </span>
                                    <button onclick="window.location.href = '{{ route('change.tariff.page') }}'">Сменить тариф</button>
                                </li>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('Скорость:') }}</strong> <span class="user-info-value">{{ $latestTariff ? $latestTariff->tariff->speed . ' Мбит/с' : 'Нет тарифа' }}</span></li>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('IPTV:') }}</strong> <span class="user-info-value">{{ $latestTariff ? $latestTariff->tariff->channels . '+ каналов' : 'Нет тарифа' }}</span></li>
                                <li class="user-info-item"><strong class="user-info-label">{{ __('Стоимость:') }}</strong> <span class="user-info-value">{{ $latestTariff ? $latestTariff->tariff->price . ' руб' : 'Нет тарифа' }}</span></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
