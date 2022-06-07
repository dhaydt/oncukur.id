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
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h3>OnLocation Service</h3>
                    {{-- <div id="button-layer d-none">
                        <button id="btnAction" onClick="locate()">My Current Location</button>
                    </div> --}}
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

                    var label = `\n        <div style='width:180px' align='center'><h5><b class='text-capitalize'>`+ value.name+ `</b></h5> <p>`+(Math.round(value.distance * 100) / 100).toFixed(2)+` KM.</p>\n <button align='center' type='button' class='btn btn-success btn-sm text-capitalize'>Show route</button>\n        </div>`

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

</script>
@endpush
