<?php

namespace blinqpay\SmartPaymentRouter;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaymentRouter
{
    protected $processors = [];



    public function addProcessor(string $name, array $details)
    {
        // Encrypt sensitive details
        if (isset($details['api_key'])) {
            $details['api_key'] = Crypt::encryptString($details['api_key']);
        }

        $this->processors[$name] = $details;
    }

    // To decrypt when needed
    public function getProcessorInfo(string $name): ?array
    {
        $processor = $this->processors[$name] ?? null;

        if ($processor && isset($processor['api_key'])) {
            $processor['api_key'] = Crypt::decryptString($processor['api_key']);
        }

        return $processor;
    }

    // Update a processor
    public function updateProcessor(string $name, array $newDetails)
    {
        if (isset($this->processors[$name])) {
            $this->processors[$name] = array_merge($this->processors[$name], $newDetails);
        }
    }

    // Remove a processor
    public function removeProcessor(string $name)
    {
        unset($this->processors[$name]);
    }

    // List all processors
    public function listProcessors(): array
    {
        return $this->processors;
    }

    public function route($transaction)
    {
        $bestProcessor = null;
        $bestScore = -1; // Higher score is better
        Log::info("Routing transaction", $transaction);
        try {
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
        } catch (\Exception $e) {
            Log::error("Routing error: " . $e->getMessage());
            throw $e; // Rethrow to handle elsewhere
        }
    }


    protected function calculateCost($transaction, $details)
    {
        // cost calculation logic
        return $transaction['amount'] * $details['fee_percentage'];
    }
}
