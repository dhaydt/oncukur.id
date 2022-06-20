<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnlocationController extends Controller
{
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
        $shop = Shop::find($request['shop_id']);
        $menu = Product::active()->where(['added_by' => 'seller', 'user_id' => $shop->seller_id])->get();

        return response()->json(['status' => 'success', 'product_data' => $menu]);
    }
}
