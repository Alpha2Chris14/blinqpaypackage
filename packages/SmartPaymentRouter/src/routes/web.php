<?php

use Illuminate\Support\Facades\Route;
use blinqpay\SmartPaymentRouter\PaymentRouter;

Route::post('/route-payment', function (PaymentRouter $router) {
    $transaction = request()->all(); // Assuming transaction details are sent as request
    $bestProcessor = $router->route($transaction);
    return response()->json(['best_processor' => $bestProcessor]);
});
