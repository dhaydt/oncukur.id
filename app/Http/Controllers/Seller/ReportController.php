<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function order_index()
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        return view('seller-views.report.order-index');
    }

    public function earning_index()
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        return view('seller-views.report.earning-index');
    }

    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));

        return back();
    }

    public function bulk_import_index()
    {
        return view('seller-views.report.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel())->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');

            return back();
        }

        $data = [];
        $skip = ['youtube_video_url', 'details'];
        foreach ($collections as $collection) {
            foreach ($collection as $key => $value) {
                if ($value === '' && !in_array($key, $skip)) {
                    Toastr::error('Please fill '.$key.' fields');

                    return back();
                }
            }

            array_push($data, [
                'seller_id' => auth('seller')->id(),
                'seller_is' => 'admin',
                'mitra_id' => null,
                'order_id' => 300000 + $collection['ORDER_ID'],
                'order_amount' => $collection['PRICE'],
                'seller_amount' => $collection['PRICE'],
                'mitra_amount' => 0,
                'admin_commission' => 0,
                'outlet_commission' => $collection['PRICE'],
                'tax' => 0,
                'payment_method' => 'Arsip',
                'received_by' => 'seller',
                'status' => 'disburse',
                'created_at' => $collection['DATE'],
                'updated_at' => $collection['DATE'],
                'delivery_charge' => 0,
                'customer_is' => $collection['CUSTOMER_NAME'],
            ]);
        }
        DB::table('order_transactions')->insert($data);
        Toastr::success(count($data).' - Transaction data imported successfully!');

        return back();
    }
}
