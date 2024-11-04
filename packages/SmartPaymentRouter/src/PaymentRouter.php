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
        $bestProcessor = null;
        $bestScore = -1; // Higher score is better

        foreach ($this->processors as $name => $details) {
            // Skip inactive processors
            if (!$details['active']) {
                continue;
            }

            // Check if processor supports the transaction's country and currency
            if (
                !in_array($transaction['country'], $details['supported_countries']) ||
                !in_array($transaction['currency'], $details['supported_currencies'])
            ) {
                continue;
            }

            // Calculate criteria
            $cost = $this->calculateCost($transaction, $details);
            $successRate = $details['success_rate'] ?? 0.95; // Default to 95% if not provided
            $responseTime = $details['average_response_time'] ?? 500; // Default to 500 ms if not provided

            // Calculate a composite score (higher is better)
            // Score weights: 50% success rate, 30% low cost, 20% response time
            $score = ($successRate * 0.5) - ($cost * 0.3) - ($responseTime / 1000 * 0.2);

            // Select the processor with the highest score
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestProcessor = $name;
            }
        }

        return $bestProcessor;
    }


    protected function calculateCost($transaction, $details)
    {
        // cost calculation logic
        return $transaction['amount'] * $details['fee_percentage'];
    }
}
