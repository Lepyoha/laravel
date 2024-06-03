<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SupportManagerController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () { return view('welcome');})->name('welcome');

Route::get('/news/popolnenie-psb', function () { return view('news/popolnenie-psb');})->name('popolneniePSB');

Route::middleware(['auth', 'checkUserType:Администратор'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');
});

Route::middleware(['auth', 'checkUserType:Менеджер технической поддержки'])->group(function () {
    Route::get('/support', [SupportManagerController::class, 'index'])->name('support.home');
});

Route::middleware(['auth', 'checkUserType:Абонент'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');
});

Route::post('/add-telegram-id', [TelegramController::class, 'addTelegramID'])->name('addTelegramID');
Route::post('/unlink-telegram-id', [TelegramController::class, 'unlinkTelegramID'])->name('unlinkTelegramID');

Route::post('/send-telegram-message', [TelegramController::class, 'sendTelegramMessage'])->name('sendTelegramMessage');

Route::post('/update-balance', [BalanceController::class, 'updateBalance'])->name('updateBalance');

Route::get('/delayed-payment', [PaymentController::class, 'showDelayedPaymentButton'])->name('delayed.payment');
Route::post('/request-delayed-payment', [PaymentController::class, 'requestDelayedPayment'])->name('request.delayed.payment');
Route::post('/confirm-delayed-payment', [PaymentController::class, 'confirmDelayedPayment'])->name('confirm.delayed.payment');
Route::post('/request-change-tariff', [PaymentController::class, 'requestChangeTariff'])->name('request.change.tariff');


Route::get('/change-tariff-page', [UserController::class, 'showChangeTariffPage'])->name('change.tariff.page');

Route::post('/change-tariff', [PaymentController::class, 'changeTariff'])->name('change.tariff');

Route::get('/freeze-account', [PaymentController::class, 'showFreezeAccountForm'])->name('freeze.account');
Route::post('/request-freeze-account', [PaymentController::class, 'requestFreezeAccount'])->name('request.freeze.account');
Route::post('/confirm-freeze-account', [PaymentController::class, 'confirmFreezeAccount'])->name('confirm.freeze.account');
