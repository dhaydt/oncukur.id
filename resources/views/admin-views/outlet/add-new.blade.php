@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Outlet_Add'))

@push('css_or_js')
<link href="{{asset('assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #map-canvas {
        height: 600px;
        width: 100%;
        margin: 0px;
        padding: 0px;
        border-radius: 20px;
    }

</style>
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page"><a
                    href="{{route('admin.product.outlet-list')}}">{{\App\CPU\translate('Outlet')}}</a>
            </li>
            <li class="breadcrumb-item">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('Add')}} </li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form class="product-form" action="{{route('admin.product.outlet-add-store')}}" method="POST"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};"
                enctype="multipart/form-data" id="product_form">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>{{ \App\CPU\Translate('Account_info') }} <small class="text-danger">(user for login as
                                outlet administrator)</small></h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="username" class="input-label">{{ \App\CPU\Translate('Full Name') }}</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="email" class="input-label">{{ \App\CPU\Translate('email') }}</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone" class="input-label">{{ \App\CPU\Translate('Phone') }}</label>
                            <input type="number" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{ \App\CPU\Translate('Outlet_info') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="input-label">{{ \App\CPU\Translate('name') }}</label>
                            <input type="text" id="name" name="name" placeholder="Outlet Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="capacity" class="input-label">{{ \App\CPU\Translate('Mitra_capacity') }}</label>
                            <input type="number" name="capacity" id="capacity" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="chair" class="input-label">{{ \App\CPU\Translate('Available_chair') }}</label>
                            <input type="text" id="chair" name="chair" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address" class="input-label mt-4">{{ \App\CPU\Translate('address') }}</label>
                            <div class="input-group">
                                <input id="address" class="form-control" name="address">
                                <btn id="map-address-btn" class="input-group-text btn btn-success" onclick="codeAddress()">Simpan Alamat</btn>
                            </div>
                            {{-- <button id="map-address-btn" onclick="codeAddress()">find</button> --}}
                            <div class="row justify-content-center mt-4">
                                <div id="map-canvas" class="mx-3"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lat" class="input-label d-none">{{ \App\CPU\Translate('Latitude') }}</label>
                            <input type="hidden" readonly id="lat" name="lat" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="long" class="input-label d-none">{{ \App\CPU\Translate('Longitude') }}</label>
                            <input type="hidden" readonly id="long" name="long" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-12 mb-4">
                                <label class="control-label">{{\App\CPU\translate('Youtube video link')}}</label>
                                <small class="badge badge-soft-danger"> ( {{\App\CPU\translate('optional, please provide
                                    embed link not direct link')}}. )</small>
                                <input type="text" name="video_link"
                                    placeholder="{{\App\CPU\translate('EX')}} : https://www.youtube.com/embed/5R06LRdUCSE"
                                    class="form-control" required>
                            </div> --}}

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Upload_outlet_image')}}</label><small
                                        style="color: red">* ( {{\App\CPU\translate('ratio')}} 1:1 )</small>
                                </div>
                                <div class="p-2 border border-dashed" style="max-width:430px;">
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="button" onclick="check()"
                                    class="btn btn-primary">{{\App\CPU\translate('Add_outlet')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{asset('assets/back-end')}}/js/tags-input.min.js"></script>
<script src="{{asset('assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap&v=weekly" defer></script> --}}
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{ env('GOOGLE_API_KEY') }}&language=id"></script>

<script>
    $(document).ready(function(){
        var searchInput = 'address';
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
            componentRestrictions: {
                country: "IDN"
            }
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
        });


        var lat = -0.287487;
        var long = 100.373011;
        var myLatlng = new google.maps.LatLng(lat, long);
        var myOptions = {
            zoom: 13,
            center: myLatlng
        }

        map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
        infoWindow = new google.maps.InfoWindow();

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
                    animation: google.maps.Animation.DROP,
                    draggable: true,
                    map: map,
                });

                // get map button functionality
        function initialize() {
                var mapOptions = {												// options for map
                    zoom: 8,
                    center: latlng
                }
                map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);	// create new map in the map-canvas div
            }

            var geocoder = new google.maps.Geocoder();

            function codeAddress(address) {
                // geocoder = new google.maps.Geocoder();
                geocoder.geocode( { 'address': address}, function(results, status) {
                    console.log('stat', status)
                    console.log('res', results)
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        infoWindow = new google.maps.InfoWindow();			// center the map on address
                        var marker = new google.maps.Marker({					// place a marker on the map at the address
                            map: map,
                            position: results[0].geometry.location
                        });
                        var label = `<span class="badge badge-success m-2 mr-3 mb-3" style="font-size: 15px;">`+ address +`</span>`
                        $("#lat").val(results[0].geometry.location.lat);
                        $("#long").val(results[0].geometry.location.lng);
                        infoWindow.setContent(label);
                        infoWindow.open(map, marker);
                    } else {
                        // alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }

            $("#map-address-btn").click(function(event){
                event.preventDefault();
                var address = $("#address").val();					// grab the address from the input field
                codeAddress(address);										// geocode the address
            });

                google.maps.event.addListener(window, 'load', initialize);
            // infoWindow.open(map, marker);	// setup initial map

                 // Mengambil alamat on drag
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var lt = results[0].geometry.location.lat
                                var lg = results[0].geometry.location.lng

                                $("#address").val(results[0].formatted_address);
                                $("#lat").val(lt);
                                $("#long").val(lg);

                                var label = `<span class="badge badge-success m-2 mr-3 mb-3" style="font-size: 15px;">`+ results[0].formatted_address +`</span>`

                                var address_components = results[0].address_components;
                                var components={};
                                jQuery.each(address_components, function(k,v1) {jQuery.each(v1.types, function(k2, v2){components[v2]=v1.long_name});});
                                var city;
                                var postal_code;
                                var state;
                                var country;

                                if(components.locality) {
                                    city = components.locality;
                                }

                                if(!city) {
                                    city = components.administrative_area_level_1;
                                }

                                if(components.postal_code) {
                                    postal_code = components.postal_code;
                                }

                                if(components.administrative_area_level_1) {
                                    state = components.administrative_area_level_1;
                                }

                                if(components.country) {
                                    country = components.country;
                                }

                                infoWindow.setContent(label);
                                infoWindow.open(map, marker);
                                }
                            }
                    });
                });
        // Akhir Mengambil alamat on drag

                marker.addListener("click", () => {
                    infoWindow = new google.maps.InfoWindow();
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


    })

        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('assets/back-end/img/400x400/img2.jpg')}}',
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('Please only input png or jpg type file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('File size too big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });
</script>

<script>
    function check(){
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                var formData = new FormData(document.getElementById('product_form'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('admin.product.outlet-add-store')}}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('{{\App\CPU\translate('outlet_added_successfully')}}!', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#product_form').submit();
                        }
                    }
                });
            })
        };
</script>
@endpush
