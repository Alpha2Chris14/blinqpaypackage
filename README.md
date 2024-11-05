# SmartPaymentRouter

The `SmartPaymentRouter` is a flexible Laravel package that provides a customizable routing solution for managing payments through multiple processors. This package dynamically selects the best payment processor based on various criteria, such as cost, availability, and country compatibility.

## Features

-   **Smart Routing Logic**: Routes transactions based on the lowest processing fee or other configurable factors.
-   **Flexible Processor Configuration**: Add and manage multiple payment processors, each with unique configurations.
-   **Extensibility**: Easily add or remove processors and expand routing logic.
-   **Country and Currency Routing**: Configure processors based on currency and geographic availability.
-   **Robust Error Handling**: Handles errors and logs issues effectively.

---

## Installation

To install the `SmartPaymentRouter` package in your Laravel application, follow these steps:

1.  **Clone the Repository**

    Clone or download this repository into the `packages` directory of your Laravel application:

    ```bash
        mkdir -p packages/blinqpaypackage
        cd packages/SmartPaymentRouter
        git clone https://github.com/Alpha2Chris14/blinqpaypackage.git
    ```

    move the content of the packages folder to your own packages folder

2.  Add the package’s namespace to your application’s composer.json file under the autoload section:

        ```json
            "autoload": {
                "psr-4": {
                    "App\\": "app/",
                    "blinqpay\\SmartPaymentRouter\\": "packages/SmartPaymentRouter/src/"
                }

            }
        ```
        After modifying composer.json, run

        ```bash
         composer dump-autoload
        ```

        to get the new autoloads

3.  Service Provider (optional)

    If you have a service provider for registering services or bindings, ensure it's loaded in your Laravel app. You may need to register it in the config/app.php file or in recent version of laravel bootstrap/provider.php.

    ```php
        return [
            App\Providers\AppServiceProvider::class,
            blinqPay\SmartPaymentRouter\SmartPaymentRouterServiceProvider::class, //add this line
        ];
    ```

Configuration

1.  Set Up Payment Processors
    To define available payment processors and set up routing logic, use the PaymentRouter class.

    Example

    In a controller or route:

    ```php
        use blinqPay\SmartPaymentRouter\PaymentRouter;

        Route::get('/test-routing', function () {
            $router = new PaymentRouter();
            $router->addProcessor('processor1', [
                'fee_percentage' => 0.01,
                'currency' => 'USD',
                'country' => 'US',
                'status' => 'active'
            ]);
            $router->addProcessor('processor2', [
                'fee_percentage' => 0.02,
                'currency' => 'USD',
                'country' => 'US',
                'status' => 'active'
            ]);

            $transaction = ['amount' => 100, 'currency' => 'USD', 'country' => 'US'];
            $bestProcessor = $router->route($transaction);

            return response()->json(['best_processor' => $bestProcessor]);
        });
    ```

## Usage

Sample Laravel Application Integration
To create a sample Laravel application that demonstrates the integration of the SmartPaymentRouter package:

    Create a New Laravel Application

    ```bash
        composer create-project --prefer-dist laravel/laravel SampleLaravelApp
        cd SampleLaravelApp
    ```
    or for version 11

    ```bash
        composer global require laravel/installer
        laravel new SampleLaravelApp
    ```

Add the Package

Follow the installation steps outlined above to add the SmartPaymentRouter to the SampleLaravelApp by placing the package in the packages directory.
Set Up a Controller

Create a new controller to handle routing:

    ```bash
        php artisan make:controller PaymentController
    ```

In app/Http/Controllers/PaymentController.php:

    ```php
        namespace App\Http\Controllers;

        use Illuminate\Http\Request;
        use blinqPay\SmartPaymentRouter\PaymentRouter;

        class PaymentController extends Controller
        {
            public function routePayment(Request $request)
            {
                $router = new PaymentRouter();
                $router->addProcessor('processor1', [
                    'fee_percentage' => 0.01,
                    'currency' => 'USD',
                    'country' => 'US',
                    'status' => 'active'
                ]);
                $router->addProcessor('processor2', [
                    'fee_percentage' => 0.02,
                    'currency' => 'USD',
                    'country' => 'US',
                    'status' => 'active'
                ]);

                $transaction = $request->all();
                $bestProcessor = $router->route($transaction);

                return response()->json(['best_processor' => $bestProcessor]);
            }
        }
    ```

Define Routes

In routes/web.php, add:

    ```php
        use App\Http\Controllers\PaymentController;

        Route::post('/route-payment', [PaymentController::class, 'routePayment']);
    ```

Testing the Integration

You can test the integration by sending a POST request to /route-payment with the transaction data:

    ```json
        {
        "amount": 100,
        "currency": "USD",
        "country": "US"
        }
    ```

You can use tools like Postman or Curl to test the endpoint.

Testing
The package includes unit tests located in packages/SmartPaymentRouter/tests. To run the tests:

    ```bash
        cd packages/SmartPaymentRouter
        vendor/bin/phpunit --testdox
    ```
