<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\mitra_wallet;
use App\Model\Mitra;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Review;
use App\Model\Shop;
use App\Model\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            $sellers = Mitra::with('shop')->where('is_email_verified', 1);
        }
        $sellers = $sellers->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.mitra.index', compact('sellers', 'search'));
    }

    public function view(Request $request, $id, $tab = null)
    {
        $seller = Mitra::with('shop', 'wallet')->findOrFail($id);
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
            return view('admin-views.mitra.view.setting', compact('seller'));
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
        $outlet_id = $order->shop_id;
        $outlet = Shop::find($outlet_id);
        $lat = $outlet->latitude;
        $lng = $outlet->longitude;
        $order->status = $request->status;
        $user = $order;
        if ($request->status == 'approved') {
            try {
                Mail::to($user->email)->send(new \App\Mail\ReviewMitra('Your registration was <i style="color:blue">APPROVED</i>, you can login on the mitra dashboard using the Email & Password you entered during registration!!! <br><br>
                (pendaftaran anda di <i style="color:blue">TERIMA</i>, anda bisa login di dashboard mitra menggunakan User & Password yang anda masukan saat pendaftaran!!)'));
                $response = translate('notification email has been sent to the mitra email');
                Toastr::success($response);
            } catch (\Exception $exception) {
                $response = translate('email_failed');
                Toastr::error($response);
            }
            DB::table('mitra_wallets')->insert([
                'mitra_id' => $order['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Toastr::success('Mitra has been approved successfully');
        } elseif ($request->status == 'rejected') {
            Toastr::info('Mitra has been rejected successfully');
        } elseif ($request->status == 'review') {
            try {
                Mail::to($user->email)->send(new \App\Mail\ReviewMitra('Your registration <i style="color:blue">ON PROGRESS</i>, Please come to <a href="https://maps.google.com/?q='.$lat.','.$lng.'">This location Outlet</a> where you apply, to verify files and skills by bringing your original identity file!! <br> The document checking period will expire within 7 days after this email is received! <br><br> (Pendaftaran anda sedang di <i style="color:blue">PROSES</i>, Mohon untuk datang ke outlet tempat anda mendaftar, agar melakukan verifikasi berkas dan skill dengan membawa berkas asli identitas anda ke <a href="https://maps.google.com/?q='.$lat.','.$lng.'">Lokasi Outlet ini</a> tempat anda melamar!! <br> Masa pemeriksaan dokumen akan berakhir dalam 7 hari setelah email ini diterima!)'));
                $response = translate('notification email has been sent to the mitra email');
                Toastr::success($response);
            } catch (\Exception $exception) {
                $response = translate('email_failed');
                Toastr::error($response);
            }
            Toastr::info('Mitra has been Reviewed');
        } elseif ($request->status == 'suspended') {
            $order->auth_token = Str::random(80);
            Toastr::info('Mitra has been suspended successfully');
        }
        $order->save();

        return back();
    }

    public function withdraw()
    {
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['mitra'])->where('mitra_id', '!=', null)
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')
            ->latest()
            ->paginate(Helpers::pagination_limit());

        return view('admin-views.mitra.withdraw', compact('withdraw_req'));
    }

    public function withdraw_view($withdraw_id, $seller_id)
    {
        $seller = WithdrawRequest::with(['mitra'])->where(['id' => $withdraw_id])->first();

        return view('admin-views.mitra.withdraw-view', compact('seller'));
    }

    public function withdrawStatus(Request $request, $id)
    {
        $withdraw = WithdrawRequest::find($id);
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request['note'];
        if ($request->approved == 1) {
            mitra_wallet::where('mitra_id', $withdraw->mitra_id)->increment('withdrawn', $withdraw['amount']);
            mitra_wallet::where('mitra_id', $withdraw->mitra_id)->decrement('pending_withdraw', $withdraw['amount']);
            $withdraw->save();
            Toastr::success('Mitras Payment has been approved successfully');

            return redirect()->route('admin.mitras.withdraw_list');
        }

        mitra_wallet::where('mitra_id', $withdraw->mitra_id)->increment('total_earning', $withdraw['amount']);
        mitra_wallet::where('mitra_id', $withdraw->mitra_id)->decrement('pending_withdraw', $withdraw['amount']);
        $withdraw->save();
        Toastr::info('Mitra Payment request has been Denied successfully');

        return redirect()->route('admin.mitras.withdraw_list');
    }
}
