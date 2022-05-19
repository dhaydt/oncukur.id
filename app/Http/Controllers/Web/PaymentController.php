<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function getPayment()
    {
        Config::$serverKey = config('midtrans.server_key');

        Config::$clientKey = config('midtrans.client_key');

        // non-relevant function only used for demo/example purpose
        $this->printExampleWarningMessage();

        // Uncomment for production environment
        // Config::$isProduction = true;

        // Enable sanitization
        Config::$isSanitized = true;

        // Enable 3D-Secure
        Config::$is3ds = true;

        // Uncomment for append and override notification URL
        // Config::$appendNotifUrl = "https://example.com";
        // Config::$overrideNotifUrl = "https://example.com";

        // Required
        $transaction_details = [
            'order_id' => rand(),
            'gross_amount' => 94000, // no decimal allowed for creditcard
        ];

        // Optional
        $item1_details = [
            'id' => 'a1',
            'price' => 18000,
            'quantity' => 3,
            'name' => 'Apple',
        ];

        // Optional
        $item2_details = [
            'id' => 'a2',
            'price' => 20000,
            'quantity' => 2,
            'name' => 'Orange',
        ];

        // Optional
        $item_details = [$item1_details, $item2_details];

        // Optional
        $billing_address = [
            'first_name' => 'Andri',
            'last_name' => 'Litani',
            'address' => 'Mangga 20',
            'city' => 'Jakarta',
            'postal_code' => '16602',
            'phone' => '081122334455',
            'country_code' => 'IDN',
        ];

        // Optional
        $shipping_address = [
            'first_name' => 'Obet',
            'last_name' => 'Supriadi',
            'address' => 'Manggis 90',
            'city' => 'Jakarta',
            'postal_code' => '16601',
            'phone' => '08113366345',
            'country_code' => 'IDN',
        ];

        // Optional
        $customer_details = [
            'first_name' => 'Andri',
            'last_name' => 'Litani',
            'email' => 'andri@litani.com',
            'phone' => '081122334455',
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address,
        ];

        // Optional, remove this to display all available payment methods
        $enable_payments = ['credit_card', 'cimb_clicks', 'mandiri_clickpay', 'echannel'];

        // Fill transaction details
        $transaction = [
            'enabled_payments' => $enable_payments,
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 10000,
            ],
        ];

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 10000,
            ],
        ];

        $snap_token = '';
        try {
            $snap_token = Snap::getSnapToken($transaction);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        echo 'snapToken = '.$snap_token;
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
