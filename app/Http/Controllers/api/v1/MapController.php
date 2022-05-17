<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Shop;

class MapController extends Controller
{
    public function explore()
    {
        $outlet = Shop::where('status', 1)->where('latitude', '!=', null)->get();

        return response()->json(Helpers::responseSuccess($outlet));
    }
}
