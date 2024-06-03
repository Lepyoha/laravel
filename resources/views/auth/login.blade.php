@extends('layouts.app')


@section('content')
    <div class="login-window">
        <div class="login-window-content">
            <div class="button-back">
                <a href="{{ route('welcome') }}">
                    На главную
                </a>
            </div>

            <div class="login-title">
                <img class="login-logo" src="../images/vnet-logo-mono.svg">
                <p>
                    Вход в личный кабинет
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="login-string">
                    <span>Введите логин</span>
                    <div class="login-string">
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Логин" value="{{ old('username') }}" required autofocus>
                        <button class="login-info" data-tooltip="Имя лицевого счёта или Имя пользователя и является Логином">
                            <i class="fas fa-question-circle"></i>
                        </button>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="password-string">
                    <span>Введите пароль</span>
                    <div class="password-string-input">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Пароль" required>
                        <button type="button" class="password-toggle" id="togglePasswordBtn">
                            <i id="eyeIcon" class="fa fa-eye-slash"></i>
                        </button>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="loginBtn">Вход</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePasswordBtn.addEventListener('click', function () {
                if (eyeIcon.classList.contains('fa-eye-slash')) {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                    passwordInput.type = 'text';
                } else {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                    passwordInput.type = 'password';
                }
            });
        });
    </script>
@endsection

