<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\Mitra;
use App\Model\Product;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnlocationController extends Controller
{
    public function getMitra(Request $request)
    {
        // return response()->json($request);
        $lat = $request->lat;
        $long = $request->long;

        foreach ($request->service_id as $s) {
            $productCheck = Product::find($s);
            if (!$productCheck) {
                return response()->json(['status' => 'failed', 'message' => 'Service not found!']);
            }
        }

        if ($lat == '') {
            return response()->json(['status' => 'error', 'message' => 'Latitude is required']);
        }
        if ($long == '') {
            return response()->json(['status' => 'error', 'message' => 'Latitude is required']);
        }
        if ($request->service_id[0] == null) {
            return response()->json(['status' => 'error', 'message' => 'Please select service menu']);
        }

        $shops = Shop::with('seller')->select('*', DB::raw('6371 * acos(cos(radians('.$lat.'))
                        * cos(radians(latitude)) * cos(radians(longitude) - radians('.$long.'))
                        + sin(radians('.$lat.')) * sin(radians(latitude)) ) AS distance'));
        $shops = $shops->having('distance', '<', 20);
        // $shops = $shops->whereHas('seller', function ($s) use ($request) {
        //     $s->with('product')->whereHas('product', function ($p) use ($request) {
        //         $p->whereJsonContains('category_ids', ['id' => (string) $request->cat_id])->inRandomOrder();
        //     });
        // });
        $shops = $shops->inRandomOrder();
        $shops = $shops->get();

        if (count($shops) > 0) {
            $shop = $shops[0];
            $prices = [];
            $id = [];
            foreach ($request->service_id as $cat) {
                if ($cat !== null) {
                    $product = Product::where('id', $cat)->pluck('unit_price');
                    $ids = Product::where('id', $cat)->pluck('id');
                    array_push($prices, $product[0]);
                    array_push($id, $ids[0]);
                }
            }
            $price = array_sum($prices);
            $driver = round(10000 * $shop->distance);

            $service = Product::find($request->service_id);

            $mitra = Mitra::with('shop')->where(['shop_id' => $shop->id, 'status' => 'approved'])->inRandomOrder()->get();

            return response()->json($mitra);
            if (count($mitra) > 0) {
                $from = [
                    'status' => 'success',
                    'range' => round($shop->distance, 2),
                    'service_price' => number_format($price),
                    'driver_price' => number_format($driver),
                    'total_price' => number_format($price + $driver),
                    'service_ids' => $id,
                    'order_type' => 'order',
                    'outlet_id' => $shop['id'],
                    'to' => [
                        'lat' => floatval($request->lat),
                        'lng' => floatval($request->long),
                    'shop' => $shop,
                    'mitra' => $mitra[0],
                    'service' => $service,
                    ],
                ];

                return response()->json($from);
            }
            $shops = ['status' => 400, 'message' => 'Mitra not available'];
            // return $shops;
            return response()->json($shops);
        } else {
            $shops = ['status' => 400, 'message' => 'Outlet Not Found in This Area'];

            return response()->json($shops);
        }
    }

    public function menuOrder()
    {
        $product = Product::where('status', 1)->get();

        $menu = [];

        foreach ($product as $p) {
            $men = [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'images' => $p->images,
                'thumbnail' => $p->thumbnail,
                'unit_price' => $p->unit_price,
                'tax' => $p->tax,
                'discount' => $p->discount,
                'details' => $p->details,
                'created_at' => $p->created_at,
                'featured_status' => $p->featured_status,
            ];
            array_push($menu, $men);
        }

        return response()->json(['status' => 'success', 'data' => $menu]);
    }

    public function index(Request $request)
    {
        $lat = $request['lat'];
        $lon = $request['lng'];

        $shops = Shop::select('*', DB::raw('6371 * acos(cos(radians('.$lat.'))
                        * cos(radians(latitude)) * cos(radians(longitude) - radians('.$lon.'))
                        + sin(radians('.$lat.')) * sin(radians(latitude)) ) AS distance'));
        $shops = $shops->having('distance', '<', 20);
        $shops = $shops->orderBy('distance', 'asc');
        $shops = $shops->get();

        return response()->json(['status' => 'success', 'data' => $shops]);
    }

    public function menu(Request $request)
    {
        // $shop = Shop::find($request['shop_id']);
        $menu = Product::active()->get();

        return response()->json(['status' => 'success', 'product_data' => $menu]);
    }
}
