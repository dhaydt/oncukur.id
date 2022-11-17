<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderManager::track_order($request['order_id']), 200);
    }

    public function place_order(Request $request)
    {
        // return response()->json($request);
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];

        // $id = $request->user()->id;
        // $user = User::with('wallet')->find($id);
        // $saldo = $user->wallet->saldo;

        foreach (CartManager::get_cart_group_ids($request) as $group_id) {
            $cart = Cart::where('cart_group_id', $group_id)->pluck('price')->toArray();
            // $belanja = array_sum($cart);
            // if ($belanja > $saldo) {
            //     return response()->json(['status' => 'fail', 'message' => 'Your balance is insufficient for this transaction']);
            // }

            $data = [
                'payment_method' => 'cash_on_delivery',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean($request);

        return response()->json(translate('order_placed_successfully'), 200);
    }
}
