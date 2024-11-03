<?php

namespace blinqPay\SmartPaymentRouter\Tests;

use PHPUnit\Framework\TestCase;
use blinqPay\SmartPaymentRouter\PaymentRouter;

class PaymentRouterTest extends TestCase
{
    public function testRouting()
    {
        $router = new PaymentRouter();
        $router->addProcessor('processor1', ['fee_percentage' => 0.01]);
        $router->addProcessor('processor2', ['fee_percentage' => 0.02]);

        $transaction = ['amount' => 100];
        $bestProcessor = $router->route($transaction);

        $this->assertEquals('processor1', $bestProcessor);
    }
}
