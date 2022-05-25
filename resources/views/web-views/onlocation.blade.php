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
                    <div id="button-layer d-none">
                        <button id="btnAction" onClick="locate()">My Current Location</button>
                    </div>
                </div>
                <div class="card-body justify-content-center d-flex">
                    <div class="map" id="map-layer" style="width: 90%; height: 500px;">
                        {{-- {!! Mapper::render() !!} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap" async defer>
</script>
<script type="text/javascript">
    $(document).ready(function(){
        locate();
    })

    // Call map
    var map;
    function initMap(lat, long) {
        var mapLayer = document.getElementById("map-layer");
        var centerCoordinates = new google.maps.LatLng(lat, long);

        var defaultOptions = { center: centerCoordinates, zoom: 13, mapTypeId: google.maps.MapTypeId.ROADMAP }

        var map = new google.maps.Map(mapLayer, defaultOptions);

        marker=new google.maps.Marker({ position: new google.maps.LatLng(lat,long),
                                        map: map,
                                        animation: google.maps.Animation.DROP
                                    });
        }

    function locate(){
        document.getElementById("btnAction").disabled = true;
        document.getElementById("btnAction").innerHTML = "Processing...";
        if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){
                var currentLatitude = position.coords.latitude;
                var currentLongitude = position.coords.longitude;

                initMap(currentLatitude, currentLongitude);

                var infoWindowHTML = "Latitude: " + currentLatitude + "<br>Longitude: " + currentLongitude;
                var infoWindow = new google.maps.InfoWindow({map: map, content: infoWindowHTML});

                var currentLocation = { lat: currentLatitude, lng: currentLongitude };
                infoWindow.setPosition(currentLocation);
                document.getElementById("btnAction").style.display = 'none';
            });
        }
    }

</script>
@endpush
