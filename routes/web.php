<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\MpesaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MpesaController::class, 'index'])->name('home');

Route::get('/stk-push', [MpesaController::class, 'stkPushForm'])->name('stk-push.form');
Route::post('/stk-push', [MpesaController::class, 'stkPush'])->name('stk-push.submit');

Route::get('/stk-query', [MpesaController::class, 'stkQueryForm'])->name('stk-query.form');
Route::post('/stk-query', [MpesaController::class, 'stkQuery'])->name('stk-query.submit');

Route::get('/c2b-simulate', [MpesaController::class, 'c2bForm'])->name('c2b.form');
Route::post('/c2b-simulate', [MpesaController::class, 'c2bSimulate'])->name('c2b.submit');

Route::get('/b2c-payment', [MpesaController::class, 'b2cForm'])->name('b2c.form');
Route::post('/b2c-payment', [MpesaController::class, 'b2cPayment'])->name('b2c.submit');

Route::get('/history', [MpesaController::class, 'history'])->name('history');

Route::post('/mpesa/callback', [CallbackController::class, 'handle'])->name('mpesa.callback');
