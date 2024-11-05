<?php

use blinqpay\SmartPaymentRouter\PaymentRouter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-routing', function () {
    $router = new PaymentRouter();
    $router->addProcessor('processor1', ['fee_percentage' => 0.01]);
    $router->addProcessor('processor2', ['fee_percentage' => 0.02]);

    $transaction = ['amount' => 100];
    $bestProcessor = $router->route($transaction);

    return response()->json(['best_processor' => $bestProcessor]);
});
