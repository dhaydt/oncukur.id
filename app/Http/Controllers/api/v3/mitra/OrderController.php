<?php

namespace App\Http\Controllers\api\v3\mitra;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        if ($request->status == 'pending') {
            $order_ids = OrderDetail::with('order')->where(['mitra_id' => $seller['id']])->whereHas('order', function ($q) {
                $q->where('order_status', 'pending');
            })->pluck('order_id')->toArray();
        } elseif ($request->status == 'processing') {
            $order_ids = OrderDetail::with('order')->where(['mitra_id' => $seller['id']])->whereHas('order', function ($q) {
                $q->where('order_status', 'processing');
            })->pluck('order_id')->toArray();
        } elseif ($request->status == 'finished') {
            $order_ids = OrderDetail::with('order')->where(['mitra_id' => $seller['id']])->whereHas('order', function ($q) {
                $q->where('order_status', 'delivered');
            })->pluck('order_id')->toArray();
        } elseif ($request->status == 'canceled') {
            $order_ids = OrderDetail::with('order')->where(['mitra_id' => $seller['id']])->whereHas('order', function ($q) {
                $q->where('order_status', 'canceled');
            })->pluck('order_id')->toArray();
        } else {
            $order_ids = OrderDetail::where(['mitra_id' => $seller['id']])->pluck('order_id')->toArray();
        }

        return response()->json(['status' => 'success', 'data' => Order::with(['customer'])->whereIn('id', $order_ids)->get()], 200);
    }

    public function details(Request $request, $id)
    {
        $data = Helpers::get_mitra_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $details = OrderDetail::where(['mitra_id' => $seller['id'], 'order_id' => $id])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json(['status' => 'success', 'data' => $details], 200);
    }

    public function order_detail_status(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $order = Order::find($request->id);

        try {
            $fcm_token = $order->customer->cm_firebase_token;
            $value = Helpers::order_status_update_message($request->order_status);
            if ($value) {
                $notif = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $notif);
            }
        } catch (\Exception $e) {
            return response()->json([]);
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => translate('order is already delivered')], 200);
        }
        $status = $request->order_status;
        if ($status == 'finished') {
            $status = 'delivered';
        }
        $order->order_status = $status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
        }

        $order->save();

        return response()->json(['status' => 'success', 'message' => translate('order_status_'.$request->order_status.'_successfully')], 200);
    }
}
