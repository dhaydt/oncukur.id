@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Outlet_Add'))

@push('css_or_js')
    <link href="{{asset('assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    enctype="multipart/form-data"
                    id="product_form">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ \App\CPU\Translate('Account_info') }}  <small class="text-danger">(user for login as outlet administrator)</small></h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="email" class="input-label">{{ \App\CPU\Translate('email') }}</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="username" class="input-label">{{ \App\CPU\Translate('username') }}</label>
                                <input type="text" class="form-control" id="username" name="username">
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
                                <label for="contact" class="input-label">{{ \App\CPU\Translate('contact_number') }}</label>
                                <input type="number" id="contact" class="form-control" name="contact">
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
                                <label for="address" class="input-label">{{ \App\CPU\Translate('address') }}</label>
                                <textarea id="address" class="form-control" name="address"></textarea>
                                <div class="row justify-content-center mt-4">
                                    <div class="map" style="width: 80%; height: 500px;">
                                        {!! Mapper::render() !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lat" class="input-label">{{ \App\CPU\Translate('Latitude') }}</label>
                                <input type="text" id="lat" name="lat" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="long" class="input-label">{{ \App\CPU\Translate('Longitude') }}</label>
                                <input type="text" id="long" name="long" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-12 mb-4">
                                    <label class="control-label">{{\App\CPU\translate('Youtube video link')}}</label>
                                    <small class="badge badge-soft-danger"> ( {{\App\CPU\translate('optional, please provide embed link not direct link')}}. )</small>
                                    <input type="text" name="video_link" placeholder="{{\App\CPU\translate('EX')}} : https://www.youtube.com/embed/5R06LRdUCSE" class="form-control" required>
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
                    </div>

                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px">
                                <button type="button" onclick="check()" class="btn btn-primary">{{\App\CPU\translate('Submit')}}</button>
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
    <script>
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
