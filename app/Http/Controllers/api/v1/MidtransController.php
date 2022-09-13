<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use Exception;
use Illuminate\Http\Request;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function createSnap(Request $request)
    {
        $customer = Helpers::get_customer($request);
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;

        $value = round(CartManager::cart_grand_total_api($customer) - $discount);
        $tran = OrderManager::gen_unique_id();
        $cart = Cart::where('customer_id', $customer->id)->first();

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

        Config::$serverKey = config('midtrans.server_key');

        Config::$clientKey = config('midtrans.client_key');

        // non-relevant function only used for demo/example purpose
        // $this->printExampleWarningMessage();

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
                'callbacks' => [
                    'finish' => env('APP_URL').'/midtrans-payment/success?cart_group_id='.$cart->cart_group_id.'&payment_id='.$tran,
                ],
            ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

            // Redirect to Snap Payment Page
            // dd($paymentUrl);

            return response()->json(['status' => 'success', 'cart_gorup_id' => $cart->cart_group_id, 'payment_id' => $tran, 'payment_url' => $paymentUrl]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function paymentSuccess(Request $request)
    {
        $group_id = $request['cart_group_id'];
        $carts = Cart::where('cart_group_id', $group_id)->get();
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach ($carts as $cart) {
            $data = [
                'payment_method' => 'Virtual Account',
                'order_status' => 'confirmed',
                'payment_status' => 'paid',
                'transaction_ref' => $request['payment_id'],
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean_api($carts);

        return response()->json(['status' => 'success', 'message' => 'Payment succeeded, order was placed'], 200);
    }
}
