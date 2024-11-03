<?php

namespace blinqpay\SmartPaymentRouter;

use Illuminate\Support\Facades\Config;

class PaymentRouter
{
    protected $processors = [];

    public function addProcessor($name, $details)
    {
        $this->processors[$name] = $details;
    }

    public function route($transaction)
    {
        // Example logic to select the best processor
        $bestProcessor = null;
        $bestCost = PHP_INT_MAX;

        foreach ($this->processors as $name => $details) {
            $cost = $this->calculateCost($transaction, $details);

            if ($cost < $bestCost) {
                $bestCost = $cost;
                $bestProcessor = $name;
            }
        }

        return $bestProcessor;
    }

    protected function calculateCost($transaction, $details)
    {
        // Example cost calculation logic
        return $transaction['amount'] * $details['fee_percentage'];
    }
}
