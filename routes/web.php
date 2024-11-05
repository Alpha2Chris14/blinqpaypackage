<?php

use blinqpay\SmartPaymentRouter\PaymentRouter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-routing', function () {
    $router = new PaymentRouter();
    $router->addProcessor('processor1', [
        'fee_percentage' => 0.01,
        'active' => true,
        'success_rate' => 0.98,
        'average_response_time' => 400,
        'supported_countries' => ['US'],
        'supported_currencies' => ['USD']
    ]);
    $router->addProcessor('processor2', [
        'fee_percentage' => 0.02,
        'active' => false,
        'success_rate' => 0.96,
        'average_response_time' => 300,
        'supported_countries' => ['NG'],
        'supported_currencies' => ['NGN']
    ]);


    $transaction = [
        'amount' => 100,
        'country' => 'US',
        'currency' => 'USD'
    ];

    $bestProcessor = $router->route($transaction);

    return response()->json(['best_processor' => $bestProcessor]);
});
