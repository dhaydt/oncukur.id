@extends('layouts.front-end.app')
@section('title', 'OnLocation')

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

</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <input type="hidden" id="user" value="{{ auth('customer')->id() }}">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h3>OnLocation Service</h3>
                    <button class="btn btn-danger btn-sm" id="reset" onclick="initMap()">Reset map</button>
                </div>
                <div class="card-body justify-content-center d-flex">
                    <div class="map" id="map" style="width: 90%; height: 500px;">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        initMaps();
    })

    var lat = -0.287487;
    var long = 100.373011;

    function initMap() {
        $('#reset').addClass('d-none');
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: lat, lng: long },
            zoom: 13,
        });

        infoWindow = new google.maps.InfoWindow();

        const locationButton = document.createElement("button");

        // locationButton.textContent = "Pan to Current Location";
        // locationButton.classList.add("custom-map-control-button");
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                var label = 'Your location'

                var marker = new google.maps.Marker({
                    position: pos,
                    animation: google.maps.Animation.BOUNCE,
                    map: map,
                });

                marker.addListener("click", () => {
                    infoWindow.setContent(label);
                    infoWindow.open(map, marker);
                });

                map.setCenter(pos);
                getOutlet(pos);
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

    function getOutlet(pos){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: `{{ route('closest-outlet') }}`,
            method: 'POST',
            data: pos,
            success: function(data){
                console.log('resp', data);
                $.each(data, function( index, value ) {
                    var position = {
                        lat: parseFloat(value.latitude),
                        lng: parseFloat(value.longitude)
                    }

                    var coordinate = JSON.stringify(position)

                    var label = `<div style='width:180px' align='center'><h5><b class='text-capitalize'>`+ value.name+ `</b></h5> <p>`+(Math.round(value.distance * 100) / 100).toFixed(2)+` KM.</p>\n <button align='center' type='button' onclick="getRoute(`+ position.lat +`,`+position.lng+`,`+value.id+`)" class='btn btn-success btn-sm text-capitalize'>Show route</button>\n        </div>`

                    var marker = new google.maps.Marker({
                        position: position,
                        animation: google.maps.Animation.DROP,
                        map: map,
                    });

                    marker.addListener("click", () => {
                        infoWindow.setContent(label);
                        infoWindow.open(map, marker);
                    });
                });
            }
        })
    }

    function getRoute(lat, lng, id){
        var user = $('#user').val();
        if(!user){
            toastr.warning('{{\App\CPU\translate('Please_login_first')}}');
            window.location = `{{ route('customer.auth.login') }}`
        }
        console.log('user', user)
        $('#reset').removeClass('d-none');
        var destination = {
            lat: lat,
            lng: lng
        }
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                showRoute(pos);
            },
            () => {
                handleLocationError(true, infoWindow, map.getCenter());
            }
            );
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }

        function showRoute(origin){
            map = new google.maps.Map(document.getElementById("map"), {
                    center: origin,
                    zoom: 13,
                });

            var directionsService = new google.maps.DirectionsService();

            var directionsDisplay = new google.maps.DirectionsRenderer();

            directionsDisplay.setMap(map);

            calculateDistance();
            function calculateDistance(){
                /**
                 * Creating a new request
                 */
                var request = {
                    origin: origin,
                    destination: destination,
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
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: `{{ route('cart.add') }}`,
                        method: 'post',
                        data: {
                            type: 'onlokasi',
                            id: id
                        },
                        success: function(data){
                            console.log('data', data)
                        }
                    })
                });
        }}
    }


</script>
@endpush
