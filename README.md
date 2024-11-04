# Smart Payment Router

## Installation

1. Add the package to your Laravel application:

2. Publish the configuration file:

php artisan vendor --provider="blinqpay\SmartPaymentRouter\SmartPaymentRouterServiceProvider"

## Usage

```php
use YourVendor\SmartPaymentRouter\PaymentRouter;

$router = app(PaymentRouter::class);
$router->addProcessor('processor1', ['fee_percentage' => 0.01]);
$bestProcessor = $router->route(['amount' => 100]);

```
