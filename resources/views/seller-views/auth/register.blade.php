@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Outlet_registration'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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
                                    <textarea name="shop_address" class="form-control" id="shop_address"rows="2" placeholder="{{\App\CPU\translate('outlet_address')}}">{{old('shop_address')}}</textarea>
                                </div>
                                <div class="row justify-content-center">
                                    <div id="map-canvas" class="mx-3"></div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <input type="text" id="lat" name="lat" class="form-control" disabled placeholder="Latitude">
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <input type="text" id="long" name="long" class="form-control" disabled placeholder="Longitude">
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
