<?php

namespace App\Http\Controllers\Mitra;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list(Request $request, $status)
    {
        $sellerId = auth('mitra')->id();
        if ($status != 'all') {
            $orders = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => $sellerId])->where(['order_status' => $status]);
        } else {
            $orders = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => $sellerId]);
        }

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = $orders->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        //dd($orders->count())
        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('mitra-views.order.list', compact('orders', 'search'));
    }
}
