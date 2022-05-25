<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function getPayment()
    {
        Config::$serverKey = config('midtrans.server_key');

        Config::$clientKey = config('midtrans.client_key');

        // non-relevant function only used for demo/example purpose
        $this->printExampleWarningMessage();

        // Uncomment for production environment
        Config::$isProduction = false;

        // Enable sanitization
        Config::$isSanitized = true;

        // Enable 3D-Secure
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'budi',
                'last_name' => 'pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
            ],
        ];
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return $snapToken;
    }

    public function printExampleWarningMessage()
    {
        if (strpos(Config::$serverKey, 'your ') != false) {
            echo '<code>';
            echo '<h4>Please set your server key from sandbox</h4>';
            echo 'In file: '.__FILE__;
            echo '<br>';
            echo '<br>';
            echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
            die();
        }
    }
}
