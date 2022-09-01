@extends('layouts.back-end.app-mitra')
@section('title',\App\CPU\translate($order['order_type'] .' Details'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <div class="page-header d-print-none p-3" style="background: white">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                        href="{{route('seller.orders.list',['all'])}}">{{\App\CPU\translate('Orders')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('Order details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate($order['order_type'])}} #{{$order['id']}}</h1>
                        @if ($order['order_type'] == 'order')
                            <input type="hidden" name="user_lat" value="{{ $order['user_lat'] }}">
                            <input type="hidden" name="user_long" value="{{ $order['user_long'] }}">
                            <input type="hidden" name="outlet_lat" value="{{ $mitra->shop->latitude }}">
                            <input type="hidden" name="outlet_long" value="{{ $mitra->shop->longitude }}">
                        @endif

                        @if($order['payment_status']=='paid')
                            <span
                                class="badge badge-soft-success {{Session::get('direction') === "rtl" ? 'mr-sm-3' : 'ml-sm-3'}}">
                            <span class="legend-indicator bg-success"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('Paid')}}
                        </span>
                        @else
                            <span
                                class="badge badge-soft-danger {{Session::get('direction') === "rtl" ? 'mr-sm-3' : 'ml-sm-3'}}">
                            <span class="legend-indicator bg-danger"
                                  style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('Unpaid')}}
                        </span>
                        @endif

                        @if($order['order_status']=='pending')
                            <span
                                class="badge badge-soft-info {{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}} text-capitalize">
                          <span class="legend-indicator bg-info text"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{str_replace('_',' ',$order['order_status'])}}
                        </span>
                        @elseif($order['order_status']=='failed')
                            <span
                                class="badge badge-danger {{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}} text-capitalize">
                          <span class="legend-indicator bg-info"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{str_replace('_',' ',$order['order_status'])}}
                        </span>
                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-primary ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-primary"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @elseif($order['order_status']=='delivered')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>{{'Finished'}}
                            </span>

                        @elseif($order['order_status']=='confirmed')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @else
                            <span
                                class="badge badge-soft-danger {{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}} text-capitalize">
                          <span class="legend-indicator bg-danger"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{str_replace('_',' ',$order['order_status'])}}
                        </span>
                        @endif
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                                <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                        </span>
                        @if(\App\CPU\Helpers::get_business_settings('order_verification'))
                            <span class="ml-2 ml-sm-3">
                                <b>
                                    {{\App\CPU\translate('order_verification_code')}} : {{$order['verification_code']}}
                                </b>
                            </span>
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <a class="text-body" target="_blank"
                           href={{route('seller.orders.generate-invoice',[$order->id])}}>
                            <i class="tio-print"></i> {{\App\CPU\translate('Print invoice')}}
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-6 mt-4">
                        </div>
                        <div class="col-6">
                            <div class="hs-unfold float-right">
                                <div class="dropdown">
                                    <select name="order_status" onchange="order_status(this.value)"
                                            class="status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{\App\CPU\translate('Pending')}}</option>
                                        <option
                                            value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{\App\CPU\translate('Confirmed')}}</option>
                                        <option
                                            value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{\App\CPU\translate('Processing')}} </option>
                                    </select>
                                </div>
                            </div>

                            @php($shipping=\App\CPU\Helpers::get_business_settings('shipping_method'))
                            @if($order['payment_method']=='cash_on_delivery' && $shipping=='sellerwise_shipping')
                                <div class="hs-unfold float-right pr-2">
                                    <div class="dropdown">
                                        <select name="payment_status" class="payment_status form-control"
                                                data-id="{{$order['id']}}">
                                            <option
                                                onclick="route_alert('{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}','{{\App\CPU\translate('Change status to paid')}} ?')"
                                                href="javascript:"
                                                value="paid" {{$order->payment_status == 'paid'?'selected':''}} >
                                                {{\App\CPU\translate('Paid')}}
                                            </option>
                                            <option
                                                value="unpaid" {{$order->payment_status == 'unpaid'?'selected':''}} >
                                                {{\App\CPU\translate('Unpaid')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3  mb-lg-0">
                <!-- Card -->
                <div class="card mb-3  mb-lg-5"
                     style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row pr-0">
                            <div class="col-12 row pr-0">
                                <div class="col-6">
                                    <h4 class="card-header-title">
                                        {{\App\CPU\translate($order['order_type'] .' details')}}
                                        <span
                                            class="badge badge-soft-dark rounded-circle {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">{{$order->details->count()}}</span>
                                    </h4>
                                </div>
                                <div class="col-6 pr-0">
                                    <div class="flex-end">
                                        <h6 class="text-capitalize mb-0"
                                            style="color: #8a8a8a;">{{\App\CPU\translate('order_type')}} :</h6>
                                        <span class="mx-1 text-capitalize badge badge-soft-primary p-1">{{$order['order_type']}}</span>
                                    </div>
                                    @if ($order['mitra_id'] !== NULL && $order['mitra_id'] !== 0)
                                    <div class="flex-end mt-2">
                                        <h6 class="mb-0" style="color: #8a8a8a;">{{\App\CPU\translate('Mitra')}}
                                            :</h6>
                                        <span class="mx-1 text-capitalize badge badge-soft-info">{{ \app\CPU\Helpers::mitra_name($order['mitra_id']) }}</span>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-md-4 product-name">
                                        <p> {{\App\CPU\translate('service_name')}}</p>
                                    </div>

                                    <div class="col col-md-2 align-self-center p-0 ">
                                        <p> {{\App\CPU\translate('price')}}</p>
                                    </div>
                                    <div class="col col-md-1 align-self-center  p-0 product-name">
                                        <p> {{\App\CPU\translate('TAX')}}</p>
                                    </div>
                                    <div class="col col-md-2 align-self-center  p-0 product-name">
                                        <p> {{\App\CPU\translate('Discount')}}</p>
                                    </div>
                                    <div
                                        class="col col-md-2 align-self-center text-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                        <p> {{\App\CPU\translate('Subtotal')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @php($subtotal=0)
                    @php($total=0)
                    @php($shipping=0)
                    @php($discount=0)
                    @php($tax=0)

                    @foreach($order->details as $detail)
                        @if($detail->product)

                            <!-- Media -->
                                <div class="media">
                                    <div class="media-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3 mb-md-0 product-name text-capitalize">
                                                <p>
                                                    {{substr($detail->product['name'],0,20)}}{{strlen($detail->product['name'])>20?'...':''}}
                                                </p>
                                            </div>

                                            <div class="col col-md-2 align-self-center p-0 ">
                                                <h6>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']))}}</h6>
                                            </div>

                                            <div class="col col-md-1 align-self-center  p-0 product-name">

                                                <h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['tax']))}}</h5>
                                            </div>
                                            <div class="col col-md-2 align-self-center  p-0 product-name">
                                                <h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['discount']))}}</h5>
                                            </div>


                                            <div
                                                class="col col-md-2 align-self-center text-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                                @php($subtotal=$detail['price']*$detail->qty+$detail['tax']-$detail['discount'])

                                                <h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @php($discount+=$detail['discount'])
                            @php($tax+=$detail['tax'])
                            @php($shipping+=$detail->shipping ? $detail->shipping->cost :0)

                            @php($total+=$subtotal)
                            <!-- End Media -->
                                <hr>
                            @endif
                        @endforeach
                        @php($shipping=$order['details'][0]['driver_cost'])
                        @php($coupon_discount=$order['discount_amount'])
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    @if ($order['order_type'] == 'order')
                                        <dt class="col-sm-6">{{\App\CPU\translate('Driver_cost')}}</dt>
                                        <dd class="col-sm-6 border-bottom">
                                            {{ \App\CPU\BackEndHelper::set_symbol($order['details'][0]['driver_cost']) }}
                                        </dd>
                                    @endif

                                    <dt class="col-sm-6">{{\App\CPU\translate('coupon_discount')}}</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}</strong>
                                    </dd>

                                    <dt class="col-sm-6">{{\App\CPU\translate('Total')}}</dt>
                                    <dd class="col-sm-6">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total+$shipping-$coupon_discount))}}</strong>
                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>

                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
                <div class="card">
                    <div class="map" id="map" style="height: 500px;"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-confirm mb-3">
                    <!-- Header -->
                    <div class="card-header px-2">
                        @if($order['order_status']=='pending')
                        <span class="badge badge-soft-warning text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('need_confirmation') }}
                        </span>
                        @elseif($order['order_status']=='failed')
                            <span class="badge badge-danger ml-sm-3 text-capitalize" style="font-size: 14px;">
                            <span class="legend-indicator bg-danger"></span>
                            {{ \App\CPU\translate('failed') }}
                            </span>
                        @elseif($order['order_status']=='confirmed')
                            <span class="badge badge-soft-success ml-sm-3 text-capitalize" style="font-size: 14px;">
                            <span class="legend-indicator bg-success"></span>
                            {{ \App\CPU\translate('Confirmed') }}
                            </span>
                        {{-- @elseif($order['order_status']=='directPay')
                            <span class="badge badge-info ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('Bayar_langsung') }}
                            </span> --}}
                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-primary text-capitalize" style="font-size: 14px;">
                                {{ \App\CPU\translate('on_process') }}
                            </span>
                        @elseif($order['order_status']=='delivered')
                            <span class="badge badge-soft-success ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('finished') }}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @endif
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if ($order->customer)
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>{{\App\CPU\translate('Customer_info')}}</h5>
                            <div class="media align-items-center" href="javascript:">
                                <div
                                    class="icon icon-soft-info icon-circle {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span class="text-body text-hover-primary"> {{\App\Model\Order::where('customer_id',$order['customer_id'])->count()}} {{\App\CPU\translate('orders')}}</span>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                Name :
                            </div>
                            <div class="mx-1">
                                <div class="media align-items-center" href="javascript:">
                                    <div class="media-body">
                                        <span class="text-body text-capitalize text-hover-primary">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</span>
                                    </div>
                                    <div
                                        class="avatar avatar-circle {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                                        <img
                                            class="avatar-img"
                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/profile/'.$order->customer->image)}}"
                                            alt="Image Description">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5>{{\App\CPU\translate('Contact info')}}</h5>
                        </div>

                        <div class="flex-start">
                            <div>
                                <i class="tio-online {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                            </div>
                            <div class="mx-1"><a class="text-dark"
                                                href="mailto: {{$order->customer['email']}}">{{$order->customer['email']}}</a>
                            </div>
                        </div>
                        <div class="flex-start mt-2">
                            <div>
                                <i class="tio-android-phone-vs {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                            </div>
                            <div class="mx-1"><a class="text-dark"
                                                href="tel:{{$order->customer['phone']}}">{{$order->customer['phone']}}</a>
                            </div>
                        </div>
                    </div>
                    @endif
                <!-- End Body -->
                @if ($order['struk'] != NULL && $order['order_status'] == 'delivered')
                <a onclick="cancel('canceled')" class="btn btn-danger w-100">
                    {{ \App\CPU\Translate('Batalkan') }}
                </a>
                @endif
                @if ($order['order_status']=='pending' )
                <div class="card-footer d-flex justify-content-center">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary w-100" onclick="order_status('canceled')">
                                {{ \App\CPU\Translate('Tolak') }}
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a class="btn w-100 btn-success" type="button" onclick="order_status('processing')">
                            {{-- @if ($stock == 0)
                            {{ \App\CPU\Translate('Kamar_habis') }}
                            @else
                            {{ \App\CPU\Translate('Terima') }}
                            @endif --}}
                            {{ \App\CPU\Translate('Terima') }}
                            </a>
                            {{-- <a onclick="order_status('processing')" class="btn btn-success w-100">
                                {{ \App\CPU\Translate('Terima') }}
                            </a> --}}
                        </div>
                    </div>
                </div>
                @endif
                @if ($order['order_status']=='processing' )
                <div class="card-footer d-flex justify-content-center">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary w-100" onclick="order_status('canceled')">
                                {{ \App\CPU\Translate('Cancel') }}
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a class="btn w-100 btn-success" type="button" onclick="order_status('delivered')">
                            {{ \App\CPU\Translate('Finish') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                </div>

                <!-- Card -->
                @php($mitra = \App\CPU\Helpers::mitra_data($order->mitra_id))
                @if($mitra)
                <div class="card mb-3">
                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-header-title">{{\App\CPU\translate('Mitra')}}</h4>
                        <div class="media align-items-center" href="javascript:">
                            <div class="media-body">
                                <span
                                    class="text-body text-capitalize text-hover-primary">{{$mitra->name}}
                                </span>
                            </div>
                            <div
                                class="avatar avatar-circle ml-3">
                                <img
                                    class="avatar-img"
                                    onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/mitra/'. $mitra->image)}}"
                                    alt="Image Description">
                            </div>

                            {{-- <div class="media-body text-right">
                                <i class="tio-chevron-right text-body"></i>
                            </div> --}}
                        </div>
                    </div>
                    <!-- End Header -->
                    <!-- Body -->
                        <div class="card-body"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Contact info')}}</h5>
                            </div>

                            <div class="flex-start">
                                <div>
                                    <i class="tio-online {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                </div>
                                <div class="mx-1"><a class="text-dark"
                                    href="mailto: {{$mitra->email}}">{{$mitra->email}}</a>
                                </div>
                            </div>
                            <div class="flex-start mt-2">
                                <div>
                                    <i class="tio-android-phone-vs {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                </div>
                                <div class="mx-1"><a class="text-dark"
                                        href="tel:{{$mitra->phone}}">{{$mitra->phone}}</a>
                                </div>
                            </div>
                        </div>
                        <!-- End Body -->
                    </div>
                @endif
                <!-- End Card -->

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{ env('GOOGLE_API_KEY') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap&v=weekly" defer></script>
    <script>
        $(document).ready(function(){
            var lat = $("input[name=user_lat]").val();
            var long = $("input[name=user_long]").val();
            console.log('coor',lat)
            initMap(lat, long);
        })

        function initMap(lat, long) {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: lat, lng: long },
                zoom: 13,
            });

            infoWindow = new google.maps.InfoWindow();

            const locationButton = document.createElement("button");

            map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    const destination = {
                        lat: parseFloat(lat),
                        lng: parseFloat(long),
                    }

                    var label = `<div style='width:180px' align='center'>\n <button align='center' type='button' onclick="getRoute(`+ parseFloat(lat) +`,`+parseFloat(long)+`)" class='btn btn-success mt-2 btn-sm text-capitalize mb-3'>Show Route</button>\n        </div>`

                    var marker = new google.maps.Marker({
                        position: pos,
                        animation: google.maps.Animation.DROP,
                        map: map,
                    });

                    marker.addListener("click", () => {
                        infoWindow.setContent(label);
                        infoWindow.open(map, marker);
                    });

                    map.setCenter(pos);
                },
                () => {
                    handleLocationError(true, infoWindow, map.getCenter());
                }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation
                ? "Error: The Geolocation service failed."
                : "Error: Your browser doesn't support geolocation."
            );
            infoWindow.open(map);
        }

        function getRoute(lat, lng){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    const dest ={
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    }
                    showRoute(pos, dest);
                },
                () => {
                    handleLocationError(true, infoWindow, map.getCenter());
                }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }

            function showRoute(origin, dest){
                console.log('route', origin, dest)
                map = new google.maps.Map(document.getElementById("map"), {
                        center: origin,
                        zoom: 13,
                    });

            var from_lat = parseFloat($("input[name=outlet_lat]").val());
            var from_long = parseFloat($("input[name=outlet_long]").val());

                var from = {
                    lat: from_lat,
                    lng: from_long
                }

                var directionsService = new google.maps.DirectionsService();

                var directionsDisplay = new google.maps.DirectionsRenderer();

                directionsDisplay.setMap(map);

                calculateDistance();
                function calculateDistance(){
                    /**
                     * Creating a new request
                     */
                    var request = {
                        origin: from,
                        destination: dest,
                        travelMode: google.maps.TravelMode.DRIVING, //WALKING, BYCYCLING, TRANSIT
                        unitSystem: google.maps.UnitSystem.IMPERIAL
                    }

                    /**
                     * Pass the created request to the route method
                     */

                    directionsService.route(request, function (result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {

                            /**
                             * Get distance and time then display on the map
                             */
                            // const output = document.querySelector('#output');
                            // output.innerHTML = "<p class='alert-success'>From: " + document.getElementById("origin").value + "</br>" +"To: " + document.getElementById("destination").value + "</br>"+"Driving distance <i class='fas fa-road'></i> : " + result.routes[0].legs[0].distance.text +"</br>"+ " Duration <i class='fas fa-clock'></i> : " + result.routes[0].legs[0].duration.text + ".</p>";

                            /**
                             * Display the obtained route
                             */
                            directionsDisplay.setDirections(result);
                        } else {
                            /**
                             * Eliminate route from the map
                             */
                            directionsDisplay.setDirections({ routes: [] });

                            /**
                             * Centre the map to my current location
                             */
                            map.setCenter(origin);

                            /**
                             * show error message in case there is any
                             */
                            // output.innerHTML = "<div class='alert-danger'><i class='fas fa-exclamation-triangle'></i> Could not retrieve driving distance.</div>";
                        }
                    });
            }}
        }


        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this?')}}',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {
                            toastr.success('{{\App\CPU\translate('Status Change successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });

        function order_status(status) {
            var value = status;
            Swal.fire({
                title: 'Are you sure to '+ status + ' this booking?',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes!')}}'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('mitra.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": value
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it !!')}}');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Status Change successfully !')}}');
                                location.reload();
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush
