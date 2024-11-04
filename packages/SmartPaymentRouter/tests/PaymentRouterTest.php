<?php

namespace blinqPay\SmartPaymentRouter\Tests;

use PHPUnit\Framework\TestCase;
use blinqPay\SmartPaymentRouter\PaymentRouter;

class PaymentRouterTest extends TestCase
{
    public function testRouting()
    {
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
            'active' => true,
            'success_rate' => 0.96,
            'average_response_time' => 300,
            'supported_countries' => ['US'],
            'supported_currencies' => ['USD']
        ]);

        $transaction = [
            'amount' => 100,
            'country' => 'US',
            'currency' => 'USD'
        ];

        $bestProcessor = $router->route($transaction);

        $this->assertEquals('processor1', $bestProcessor);
    }
}
