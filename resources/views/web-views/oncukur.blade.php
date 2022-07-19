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
                    <h3>Select Service to Order</h3>
                </div>
                <div class="card-body justify-content-center d-flex">
                    @foreach ($product as $p)
                    <div class="card-menu col-md-2">
                        <a href="javascript:" class="card shadow-sm nav-link" onclick="submit({{ $p->id }})">
                            <form action="" method="post" id="menu{{ $p->id }}">
                                @csrf
                                <input type="hidden" value="{{ $p->id }}" name="cat_id">
                                <input type="hidden" name="lat">
                                <input type="hidden" name="long">
                                <div class="card-header menu-item text-center">
                                    <h4>
                                        {{ $p->name }}
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <img class="cat-img" src="{{ asset('storage/category/').'/'.$p->icon }}" alt="">
                                </div>
                            </form>
                        </a>
                    </div>
                    @endforeach
                </div>
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
                    <input type="hidden" id="product_id" name="id[]">
                    <input type="hidden" name="mitra_id">
                    <input type="hidden" name="range">
                    <input type="hidden" name="order_type" value="order">
                    <div class="modal-body" id="menu">
                        <div class="d-flex justify-content-center">
                            <img src="" alt="" class="mitra-avatar"
                                onerror="this.src=`{{ asset('assets/front-end/img/def.png') }}`">
                        </div>
                        <div class="d-flex justify-content-center mt-4 flex-column text-center">
                            <h4 class="text-bold" id="mitra-name"></h4>
                            <span id="distance"></span>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <div id="map" style="height: 500px; width: 100%;"></div>
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
        }
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
                // getRoute(parseFloat(lat), parseFloat(lng), id)
            }
        })
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
            var form = $('#menu' + id).serialize();

            $.ajax({
                url: `{{ route('menu.location') }}`,
                method: 'POST',
                data: form,
                beforeSend: function () {
                        $('#getMitra').removeClass('d-none');
                },
                success: function(resp){
                    console.log('resp', resp);
                        if(resp.length == 0){
                            Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: "Service Not Found",
                            showConfirmButton: true,
                            // timer: 1500
                        });
                    }else{
                        console.log('resp', resp)
                        var from = {
                            lat: parseFloat(resp.mitra.shop.latitude),
                            lng: parseFloat(resp.mitra.shop.longitude)
                        }
                        initMaps(from, resp.to);
                        $('#mitra-name').text(resp.mitra.name);
                        $('#distance').text((Math.round(resp.shop.distance * 100) / 100).toFixed(2) + ' KM');
                        $('input[name=mitra_id]').val(resp.mitra.id)
                        $('#product_id').val(resp.service.id)
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
