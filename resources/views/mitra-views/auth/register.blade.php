@extends('layouts.front-end.app-mitra')

@section('title',\App\CPU\translate('Mitra_apply'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media(max-width: 600px) {
        .main-card {
            width: 100% !important;
            margin-top: 15%;
        }
    }

</style>
@endpush

@section('content')

<div class="container main-card rtl" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">

    <div class="card o-hidden border-0 shadow-lg my-4">
        <div class="card-body ">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center mb-2 ">
                            <h3 class=""> {{\App\CPU\translate('Mitra_OnCukur')}} {{\App\CPU\translate('Application')}}
                            </h3>
                            <hr>
                        </div>
                        <form class="user" action="{{route('mitra.auth.register.store')}}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <h5 class="black">Outlet</h5>
                            <div class="form-group row">
                                <div class="col-sm-12 col-12 mb-3 mb-sm-0">
                                    <select
                                        class="js-example-basic-single w-100 form-control js-states js-example-responsive demo-select2"
                                        name="outlet_id" required>
                                        <option value="">-- Select outlet --</option>
                                        @foreach ($outlet as $o)
                                        <option value="{{ $o->id }}">{{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h5 class="black">{{\App\CPU\translate('Mitra')}} {{\App\CPU\translate('Info')}} </h5>
                            <div class="form-group row">

                                <div class="col-md-6 col-12 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                        name="name" value="{{old('name')}}"
                                        placeholder="{{\App\CPU\translate('full_name')}}" required>
                                </div>

                                <div class="col-md-6 col-12 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="datepicker"
                                        name="birthdate" value="{{old('birtdate')}}"
                                        placeholder="{{\App\CPU\translate('Birthdate')}}" required>
                                </div>

                            </div>
                            <div class="form-group row">

                                <div class="col-12 col-md-6 col-md-6 mb-3">
                                    <input type="email" class="form-control form-control-user" id="email" name="email"
                                        value="{{ old('email') }}"
                                        placeholder="{{ \App\CPU\translate('email_address') }}" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <input type="number" class="form-control form-control-user" id="exampleInputPhone"
                                        name="phone" value="{{old('phone')}}"
                                        placeholder="{{\App\CPU\translate('phone_number')}}" required>
                                </div>

                            </div>
                            <div class="form-group row">

                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" minlength="6"
                                        id="exampleInputPassword" name="password"
                                        placeholder="{{\App\CPU\translate('password')}}" required>
                                </div>

                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" minlength="6"
                                        id="exampleRepeatPassword"
                                        placeholder="{{\App\CPU\translate('repeat_password')}}" required>
                                    <div class="pass invalid-feedback">{{\App\CPU\translate('Repeat')}}
                                        {{\App\CPU\translate('password')}} {{\App\CPU\translate('not match')}} .</div>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">{{\App\CPU\translate('Upload_KTP_Mitra')}} <span class="text-danger" style="font-size: 10px">({{\App\CPU\translate('Max size 2 Mb')}})</span> </label>
                                    </div>

                                    <div style="max-width:200px;">
                                        <div class="row" id="thumbnail"></div>
                                    </div>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block"
                                id="apply">{{\App\CPU\translate('Apply')}} {{\App\CPU\translate('OnCukur_Mitra')}}
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small"
                                href="{{route('mitra.auth.login')}}">{{\App\CPU\translate('already_have_an_account?_login_here.')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{asset('assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
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
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

    $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'ktp',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12',
                maxFileSize: '2000000',
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

    $( function() {
        $( "#datepicker" ).datepicker({
            shortYearCutoff: 1,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-m-d',
            minDate: "-90Y",
            maxDate: "0Y",
            yearRange: "1942:now"
        });
    } );

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
