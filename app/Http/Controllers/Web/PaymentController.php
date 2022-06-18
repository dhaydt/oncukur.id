<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function createSnap(Request $request)
    {
        if (auth('customer')->check()) {
            $customer = auth('customer')->user();
            $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
            $value = CartManager::cart_grand_total() - $discount;
            $tran = OrderManager::gen_unique_id();

            $products = [];
            foreach (CartManager::get_cart() as $detail) {
                array_push($products, [
                'name' => $detail->product['name'],
            ]);
            }

            $user = [
            'given_names' => $customer->f_name,
            'email' => $customer->email,
            'mobile_number' => $customer->phone,
        ];

            session()->put('transaction_ref', $tran);

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
                    'order_id' => $tran,
                    'gross_amount' => $value,
                ],
            ];

            try {
                // Get Snap Payment Page URL
                $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

                // Redirect to Snap Payment Page
                dd($paymentUrl);

                return redirect()->away($paymentUrl);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            Toastr::warning('Please login first!');

            return redirect()->route('customer.auth.login');
        }
    }

    public function paymentSuccess()
    {
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach (CartManager::get_cart_group_ids() as $group_id) {
            $data = [
                'payment_method' => 'Virtual Account',
                'order_status' => 'confirmed',
                'payment_status' => 'paid',
                'transaction_ref' => session('transaction_ref'),
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }
        CartManager::cart_clean();
        if (auth('customer')->check()) {
            Toastr::success('Payment success.');

            return view('web-views.checkout-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }

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
