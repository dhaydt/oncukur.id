<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Review;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $sellers = Mitra::with('outlet')
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $sellers = Mitra::with('outlet');
        }
        $sellers = $sellers->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.mitra.index', compact('sellers', 'search'));
    }

    public function view(Request $request, $id, $tab = null)
    {
        $seller = Mitra::with('shop')->findOrFail($id);
        if ($tab == 'order') {
            $id = $seller->id;
            $orders = Order::where(['seller_is' => 'seller'])->where(['seller_id' => $id])->latest()->paginate(Helpers::pagination_limit());
            // $orders->map(function ($data) {
            //     $value = 0;
            //     foreach ($data->details as $detail) {
            //         $value += ($detail['price'] * $detail['qty']) + $detail['tax'] - $detail['discount'];
            //     }
            //     $data['total_sum'] = $value;
            //     return $data;
            // });
            return view('admin-views.mitra.view.order', compact('seller', 'orders'));
        } elseif ($tab == 'product') {
            $products = Product::where('added_by', 'seller')->where('user_id', $seller->id)->paginate(Helpers::pagination_limit());

            return view('admin-views.mitra.view.product', compact('seller', 'products'));
        } elseif ($tab == 'setting') {
            $commission = $request['commission'];
            if ($request->has('commission')) {
                request()->validate([
                    'commission' => 'required | numeric | min:1',
                ]);

                if ($request['commission_status'] == 1 && $request['commission'] == null) {
                    Toastr::error('You did not set commission percentage field.');
                //return back();
                } else {
                    $seller = Mitra::find($id);
                    $seller->sales_commission_percentage = $request['commission_status'] == 1 ? $request['commission'] : null;
                    $seller->save();

                    Toastr::success('Commission percentage for this seller has been updated.');
                }
            }
            $commission = 0;
            if ($request->has('gst')) {
                if ($request['gst_status'] == 1 && $request['gst'] == null) {
                    Toastr::error('You did not set GST number field.');
                //return back();
                } else {
                    $seller = Mitra::find($id);
                    $seller->gst = $request['gst_status'] == 1 ? $request['gst'] : null;
                    $seller->save();

                    Toastr::success('GST number for this seller has been updated.');
                }
            }

            //return back();
            return view('admin-views.seller.view.setting', compact('seller'));
        } elseif ($tab == 'transaction') {
            $transactions = OrderTransaction::where('seller_is', 'seller')->where('seller_id', $seller->id);

            $query_param = [];
            $search = $request['search'];
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $transactions = $transactions->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('order_id', 'like', "%{$value}%")
                            ->orWhere('transaction_id', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            } else {
                $transactions = $transactions;
            }
            $status = $request['status'];
            if ($request->has('status')) {
                $key = explode(' ', $request['status']);
                $transactions = $transactions->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('status', 'like', "%{$value}%");
                    }
                });
                $query_param = ['status' => $request['status']];
            }
            $transactions = $transactions->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

            return view('admin-views.mitra.view.transaction', compact('seller', 'transactions', 'search', 'status'));
        } elseif ($tab == 'review') {
            $sellerId = $seller->id;

            $query_param = [];
            $search = $request['search'];
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $product_id = Product::where('added_by', 'seller')->where('user_id', $sellerId)->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    }
                })->pluck('id')->toArray();

                $reviews = Review::with(['product'])
                    ->whereIn('product_id', $product_id);

                $query_param = ['search' => $request['search']];
            } else {
                $reviews = Review::with(['product'])->whereHas('product', function ($query) use ($sellerId) {
                    $query->where('user_id', $sellerId)->where('added_by', 'seller');
                });
            }
            //dd($reviews->count());
            $reviews = $reviews->paginate(Helpers::pagination_limit())->appends($query_param);

            return view('admin-views.mitra.view.review', compact('seller', 'reviews', 'search'));
        }

        return view('admin-views.mitra.view', compact('seller'));
    }

    public function updateStatus(Request $request)
    {
        $order = Mitra::findOrFail($request->id);
        $order->status = $request->status;
        if ($request->status == 'approved') {
            Toastr::success('Mitra has been approved successfully');
        } elseif ($request->status == 'rejected') {
            Toastr::info('Mitra has been rejected successfully');
        } elseif ($request->status == 'suspended') {
            $order->auth_token = Str::random(80);
            Toastr::info('Mitra has been suspended successfully');
        }
        $order->save();

        return back();
    }
}
