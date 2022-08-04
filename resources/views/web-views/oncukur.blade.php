@extends('layouts.front-end.app')
@section('title', 'OnCukur')

@push('css_or_js')
<style>
    #btnAction {
        background: #3878c7;
        padding: 10px 40px;
        border: #3672bb 1px solid;
        border-radius: 2px;
        color: #FFF;
        font-size: 0.9em;
        cursor: pointer;
        display: block;
        z-index: 200;
    }

    #btnAction:disabled {
        background: #6c99d2;
    }

    .cat-img {
        height: 100px;
    }

    .menu-item {
        background: {{ $web_config['secondary_color'] }};
        border-radius: 20px;
        transition: 0.2s;
    }

    .card-menu:hover .menu-item {
        background: {{ $web_config['primary_color'] }}
    }

    .mitra-avatar {
        height: 100px;
        border-radius: 50%;

        border: 2px solid {{ $web_config['primary_color'] }};

        background: {{ $web_config['primary_color'] }};
    }
    .container-checkbox{
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
    }

    .container-checkbox div{
        margin: 10px;
    }

    .container-checkbox div label{
        cursor: pointer;
        position: relative;
    }

    .container-checkbox div label input[type='checkbox']{
        position: absolute;
        z-index: 1;
        width: 100%;
        height: 100%;
        cursor: pointer;
        opacity: 0;
    }

    .container-checkbox div label span {
        position: relative;
        display: inline-block;
        background: {{$web_config['primary_color']}};
        padding: 15px 30px;
        color: #fff;
        text-shadow: 0 1px 4px rgba(0,0,0,.5);
        border-radius: 30px;
        font-size: 20px;
        user-select: none;
        font-weight: 700;
        overflow: hidden;
    }

    .container-checkbox div label span::before{
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background: #ffffff1a;
    }
    .container-checkbox div label input[type="checkbox"]:checked ~ span{
        background-color: {{$web_config['secondary_color']}};
        color: #fbfbfb;
        box-shadow: 0 2px 20px {{$web_config['secondary_color']}};
    }
    /* -------- */

    .check-input{
        display: none
    }
    span.check-menu{
        position: relative;
        display: inline-block;
        background: #424242;
        padding: 15px 30px;
        color: #555;
        text-shadow: 0 1px 4px rgba(0,0,0,.5);
        border-radius: 30px;
        font-size: 20px;
        user-select: none;
        overflow: hidden;
    }
    span.check-menu::before{
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background: rgba(255,255,255,.1);
    }

    .checkbox input[type="checkbox"]:checked ~ span.check-menu{
        background-color: wheat;
    }

</style>
@endpush

@section('content')
<div class="container">
    <div class="col-12"
        style="width:100vw;position: fixed;z-index: 9999;display: flex;align-items: center;justify-content: center; left:0;">
        <div id="getMitra" class="d-none">
            <img width="200"
                src="{{asset('storage/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
                onerror="this.src='{{asset('public/assets/front-end/img/loader.gif')}}'">
        </div>
    </div>
    <div class="row">
        <input type="hidden" id="user" value="{{ auth('customer')->id() }}">
        <div class="col-12">
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <h3>Select Services to Order</h3>
                </div>
                <form action="" method="post" id="menu">
                    @csrf
                <div class="card-body checkbox justify-content-center d-flex">
                    <div class="container-checkbox">
                    @foreach ($product as $p)
                        <div class="">
                            <label for="">
                                <input type="checkbox" name="cat_id[]" value="{{ $p->id }}">
                                <span class="text-capitalize">{{ $p->name }}</span>
                            </label>
                        </div>
                    {{-- <div class="card-menu col-md-2">
                        <input type="checkbox" class="form-check-input check-input" value="{{ $p->id }}" name="cat_id[]">
                        <span class="check-menu">
                                <div class="card-header menu-item text-center text-capitalize">
                                        {{ $p->name }}
                                </div>
                                <div class="card-body text-center">
                                    <img class="cat-img" src="{{ asset('storage/product/').'/'.$p->images }}" alt="">
                                </div>
                        </span>
                    </div> --}}
                    @endforeach
                    </div>
                </div>
                <div class="card-footer text-end">
                        <input type="hidden" name="lat">
                        <input type="hidden" name="long">

                        <a href="javascript:" class="btn btn-primary" onclick="submit({{ $p->id }})"><i class="fas fa-search"></i> Find Mitra</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mitra Found</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="menuForm">
                    <input type="hidden" name="mitra_id">
                    <input type="hidden" name="range">
                    <input type="hidden" name="order_type" value="order">
                    <div class="modal-body" id="menu">
                        <div class="d-flex justify-content-center">
                            <img src="" alt="" class="mitra-avatar"
                                onerror="this.src=`{{ asset('assets/front-end/img/def.png') }}`">
                        </div>
                        <div class="d-flex justify-content-center mt-4 flex-column text-center">
                            <h4 class="text-bold text-capitalize" id="mitra-name"></h4>
                            <span id="distance"></span>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <div id="map" style="height: 500px; width: 100%;"></div>
                        </div>
                    </div>
                    <div class="row px-4">
                        <div class="d-flex justify-content-between">
                            <span class="prices text-bold">Service Price</span>
                            <span id="sPrices"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="prices text-bold">Driver Price</span>
                            <span id="dPrices"></span>
                        </div>
                        <div class="row">
                            <hr>
                        </div>
                        <div class="d-flex justify-content-end">
                            <h5 class="text-success" id="totalPrice"></h5>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="booking()" class="btn btn-primary">Order Now</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function booking(){
        var user = $('#user').val();
        if(!user){
            toastr.warning('{{\App\CPU\translate('Please_login_first')}}');
            window.location = `{{ route('customer.auth.login') }}`
            // break;
        }else{
            var data = $('#menuForm').serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: `{{ route('cart.add') }}`,
                data: data,
                method: 'POST',
                success: function(data){
                    var lat = $('.lat').val();
                    var lng = $('.lng').val();
                    var id = $('.id').val();
                    $('#modalMenu').modal('hide');
                    toastr.success('Order placed successfully!!');
                    updateNavCart();
                    location.href = '/shop-cart';
                    // getRoute(parseFloat(lat), parseFloat(lng), id)
                }
            })
        }
    }
    $(document).ready(function(){
            if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                console.log('pos', pos)
                $('input[name=lat]').val(pos.lat);
                $('input[name=long]').val(pos.lng);

            },
            () => {
                handleLocationError(true, infoWindow, map.getCenter());
            }
            );
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }

        })

        function submit(id){
            var form = $('#menu').serialize();
            console.log('form', form)

            $.ajax({
                url: `{{ route('menu.location') }}`,
                method: 'POST',
                data: form,
                beforeSend: function () {
                        $('#getMitra').removeClass('d-none');
                },
                success: function(resp){
                    console.log('resp', resp);
                        if(resp.status == 400){
                            Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: resp.message,
                            showConfirmButton: true,
                            // timer: 1500
                        });
                    }else{
                        var from = {
                            lat: parseFloat(resp.mitra.shop.latitude),
                            lng: parseFloat(resp.mitra.shop.longitude)
                        }
                        initMaps(from, resp.to);
                        $.each(resp.ids, function(index, val){
                            $('#menuForm').append(`<input type="hidden" value="`+val+`" name="id[]">`)
                        })
                        $('#mitra-name').text(resp.mitra.name);
                        $('#distance').text((Math.round(resp.shop.distance * 100) / 100).toFixed(2) + ' KM');
                        $('input[name=mitra_id]').val(resp.mitra.id)
                        $('#product_id').val(resp.service.id)
                        $('#sPrices').text('Rp '+resp.service_price)
                        $('#dPrices').text('Rp ' + resp.driver_price)
                        $('#totalPrice').text('Rp ' + resp.total_price)
                        $('input[name=range]').val((Math.round(resp.shop.distance * 100) / 100).toFixed(2))
                        $('.mitra-avatar').attr('src', `{{ asset('storage/mitra') }}`+ '/' + resp.image)
                        $('#modalMenu').modal('show');
                    }
                    $('#getMitra').addClass('d-none');
                }
            })
        }

        function initMaps(from, to) {
            console.log('res', from, to)
            map = new google.maps.Map(document.getElementById("map"), {
                center: to,
                zoom: 12,
            });

            // infoWindow = new google.maps.InfoWindow();

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
                    destination: to,
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
            }
        }

</script>
@endpush
