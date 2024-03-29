<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $user = Helpers::get_customer($request);
        $cart = Cart::where(['customer_id' => $user->id])->get();
        $cart->map(function ($data) {
            $shop = Shop::where('seller_id', $data['seller_id'])->first();
            $data['choices'] = json_decode($data['choices']);
            $data['variations'] = json_decode($data['variations']);
            $data['outlet_name'] = $shop['name'];
            $data['outlet_address'] = $shop['address'];
            $data['latitude'] = $shop['latitude'];
            $data['longitude'] = $shop['longitude'];

            return $data;
        });

        return response()->json($cart, 200);
    }

    public function add_to_cart(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'fail', 'message' => 'Please login first!']);
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
            'id.required' => translate('Service ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $cart = CartManager::add_to_cart($request);

        return response()->json(['status' => 'success', 'data' => $cart]);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'quantity' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $response = CartManager::update_cart_qty($request);

        return response()->json($response);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = Helpers::get_customer($request);
        Cart::where(['id' => $request->key, 'customer_id' => $user->id])->delete();

        return response()->json(translate('successfully_removed'));
    }
}
