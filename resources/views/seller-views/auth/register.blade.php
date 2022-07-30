<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="_token" content="{{csrf_token()}}">
    <title>Outlet Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
        }

        .column {
            min-height: 100vh;
        }
        .helpers{
            font-size: 12px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center h-100">
            <div class="col-12 col-md-12 column d-flex align-items-center">
                <form action="{{ route('shop.apply') }}" class="w-100" method="post" enctype="multipart/form-data">
                <div class="card shadow-lg w-100 my-5">
                    <div class="card-header border-0 shadow-sm">
                        <div class="card-title text-center py-4">
                            <h5>
                                Outlet Registration
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">

                            @csrf
                            <div class="card shadow-sm border-0">
                                <div class="card-header border-0 shadow-sm">
                                    <div class="card-title">
                                        Admin Info
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12  col-lg-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Full Name</label>
                                                <input type="text" class="form-control form-control-user" name="name" id="name" value="{{ old('name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail1" value="{{ old('email') }}"
                                                    aria-describedby="emailHelp" required>
                                                <div id="emailHelp" class="form-text text-primary helpers ms-1">Use for Outlet login
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6  col-lg-4">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone number</label>
                                                <input type="number" name="phone" value="{{ old('phone') }}" class="form-control form-control-user" aria-describedby="phoneHelp" required>
                                                <div id="phoneHelp" class="form-text text-primary helpers ms-1">Ex: 0812 3456 7899</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control form-control-user" minlength="6" required name="password">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="r_password" class="form-label">Repeat Password</label>
                                                <input type="password" class="form-control form-control-user" minlength="6" required>
                                                <div class="pass invalid-feedback">Repeat Password Not Match</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-sm mt-4 border-0">
                                <div class="card-header border-0 shadow-sm">
                                    <div class="card-title">
                                        Outlet Info
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Full Name</label>
                                                <input type="text" class="form-control form-control-user" name="name" id="name" value="{{ old('name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail1" value="{{ old('email') }}"
                                                    aria-describedby="emailHelp" required>
                                                <div id="emailHelp" class="form-text text-primary helpers ms-1">Use for Outlet login
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone number</label>
                                                <input type="number" name="phone" value="{{ old('phone') }}" class="form-control form-control-user" aria-describedby="phoneHelp" required>
                                                <div id="phoneHelp" class="form-text text-primary helpers ms-1">Ex: 0812 3456 7899</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control form-control-user" minlength="6" required name="password">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="r_password" class="form-label">Repeat Password</label>
                                                <input type="password" class="form-control form-control-user" minlength="6" required>
                                                <div class="pass invalid-feedback">Repeat Password Not Match</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success ms-auto m-2" disabled id="apply">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
</body>

</html>

{{-- <!--
Real register
@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Outlet_registration'))

@push('css_or_js')
<link href="{{asset('assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
<link href="{{asset('assets/back-end/css/croppie.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<style>
    #map-canvas{
        height: 500px;
        margin-top: 20px;
        border-radius: 10px;
    }
</style>
@section('content')

<div class="container main-card rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

    <div class="card o-hidden border-0 shadow-lg my-4">
        <div class="card-body ">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center mb-2 ">
                            <h3 class="" > {{\App\CPU\translate('Outlet')}} {{\App\CPU\translate('Registration')}}</h3>
                            <hr>
                        </div>
                        <form class="user" action="{{route('shop.apply')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <h5 class="black">{{\App\CPU\translate('Admin')}} {{\App\CPU\translate('Info')}} </h5>
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="exampleFirstName" name="name" value="{{old('name')}}" placeholder="{{\App\CPU\translate('full_name')}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="email" class="form-control form-control-user" id="email" name="email" value="{{ old('email') }}" placeholder="{{ \App\CPU\translate('email_address') }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control form-control-user" id="exampleInputPhone" name="phone" value="{{old('phone')}}" placeholder="{{\App\CPU\translate('phone_number')}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" minlength="6" id="exampleInputPassword" name="password" placeholder="{{\App\CPU\translate('password')}}" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" minlength="6" id="exampleRepeatPassword" placeholder="{{\App\CPU\translate('repeat_password')}}" required>
                                    <div class="pass invalid-feedback">{{\App\CPU\translate('Repeat')}}  {{\App\CPU\translate('password')}} {{\App\CPU\translate('not match')}} .</div>
                                </div>
                            </div>

                            <h5 class="black">{{\App\CPU\translate('Outlet')}} {{\App\CPU\translate('Info')}}</h5>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0 col-md-4">
                                    <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name" placeholder="{{\App\CPU\translate('Outlet_name')}}" value="{{old('shop_name')}}"required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0 col-md-4">
                                    <input type="number" class="form-control form-control-user" name="capacity" placeholder="{{\App\CPU\translate('Mitra_capacity')}}" value="{{old('capacity')}}"required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0 col-md-4">
                                    <input type="number" class="form-control form-control-user" name="chair" placeholder="{{\App\CPU\translate('Available_chair')}}" value="{{old('chair')}}"required>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="text" name="shop_address" class="form-control" id="shop_address" placeholder="{{\App\CPU\translate('outlet_address')}}" />
                                </div>
                                <div class="row justify-content-center">
                                    <div id="map-canvas" class="mx-3"></div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <input type="hidden" id="lat" name="lat" class="form-control" disabled placeholder="Latitude">
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <input type="hidden" id="long" name="long" class="form-control" disabled placeholder="Longitude">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block" id="apply">{{\App\CPU\translate('register')}} {{\App\CPU\translate('Outlet')}} </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small"  href="{{route('seller.auth.login')}}">{{\App\CPU\translate('already_have_an_account?_login_here.')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
<script>
    $(document).ready(function(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                console.log('pos', pos)
                initMap(pos);


                const input = document.getElementById("shop_address");
                const options = {
                    // fields: ["formatted_address", "geometry", "name"],
                    types: ["geocode"],
                };
                const autocomplete = new google.maps.places.Autocomplete(input, options);

                google.maps.event.addListener(autocomplete, 'place_changed', function(){
                    var near_place = autocomplete.getPlace();
                })

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

    function initMap(center){
        const map = new google.maps.Map(document.getElementById("map-canvas"), {
            center: center,
            zoom: 12,
        });
    }

    $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup',function () {
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass==passRepeat){
            $('.pass').hide();
        }
        else{
            $('.pass').show();
        }
    });
    $('#apply').on('click',function () {

        var image = $("#image-set").val();
        if (image=="")
        {
            $('.image').show();
            return false;
        }
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass!=passRepeat){
            $('.pass').show();
            return false;
        }


    });
    function Validate(file) {
        var x;
        var le = file.length;
        var poin = file.lastIndexOf(".");
        var accu1 = file.substring(poin, le);
        var accu = accu1.toLowerCase();
        if ((accu != '.png') && (accu != '.jpg') && (accu != '.jpeg')) {
            x = 1;
            return x;
        } else {
            x = 0;
            return x;
        }
    }

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

    function readlogoURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerLogo').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#LogoUpload").change(function () {
        readlogoURL(this);
    });
    $("#BannerUpload").change(function () {
        readBannerURL(this);
    });
</script>
@endpush

--> --}}
