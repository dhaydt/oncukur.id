<?php

namespace App\Http\Controllers\Mitra;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
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

    public function details($id)
    {
        $mitra = auth('mitra')->id();
        $sellerId = auth('mitra')->id();
        $order = Order::with(['details' => function ($query) use ($sellerId) {
            $query->where('mitra_id', $sellerId);
        }])->with('customer', 'shipping')
            ->where('id', $id)->first();

        $mitra = Mitra::with('shop')->find($mitra);

        return view('mitra-views.order.order-details', compact('order', 'mitra'));
    }

    public function status(Request $request)
    {
        $order = Order::find($request->id);
        $fcm_token = $order->customer->cm_firebase_token;
        $value = Helpers::order_status_update_message($request->order_status);
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            return response()->json([]);
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already finished.'], 200);
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        // if ($request->order_status == 'delivered' && $order['mitra_id'] != null) {
        //     $cost =
        //     OrderManager::wallet_manage_on_order_status_change($order, 'mitra');
        // }

        $order->save();
        $data = $request->order_status;

        return response()->json($data);
    }
}
