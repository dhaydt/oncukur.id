@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Outlet Edit'))

@push('css_or_js')
    <link href="{{asset('assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #map-canvas {
            height: 100%;
            width: 100%;
            margin: 0px;
            padding: 0px;
            border-radius: 20px;
        }

    </style>
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('admin.product.outlet-list')}}">{{\App\CPU\translate('Outlet')}}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Edit')}}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form class="product-form" action="{{route('admin.product.outlet-update',$shop->id)}}" method="post"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    enctype="multipart/form-data"
                    id="product_form">
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            <h4>{{ \App\CPU\Translate('Outlet_info') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="input-label">{{ \App\CPU\Translate('name') }}</label>
                                <input type="text" id="name" name="name" value="{{ $shop->name }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="contact" class="input-label">{{ \App\CPU\Translate('contact_number') }}</label>
                                <input type="number" id="contact" class="form-control" name="contact" value="{{ $shop->contact }}">
                            </div>
                            <div class="form-group">
                                <label for="capacity" class="input-label">{{ \App\CPU\Translate('Mitra_capacity') }}</label>
                                <input type="number" name="capacity" id="capacity" class="form-control" value="{{ $shop->capacity }}">
                            </div>
                            <div class="form-group">
                                <label for="chair" class="input-label">{{ \App\CPU\Translate('Available_chair') }}</label>
                                <input type="text" id="chair" name="chair" class="form-control" value="{{ $shop->chair }}">
                            </div>
                            <div class="form-group">
                                <div class="row justify-content-center mt-4">
                                    <div class="map" style="width: 80%; height: 500px;">
                                        <div id="map-canvas" class="mx-3"></div>
                                    </div>
                                </div>
                                <label for="address" class="input-label">{{ \App\CPU\Translate('address') }}</label>
                                <textarea id="address" class="form-control" name="address">{{ $shop->address }}</textarea>
                            </div>
                            <div class="form-group">
                                {{-- <label for="lat" class="input-label">{{ \App\CPU\Translate('Latitude') }}</label> --}}
                                <input type="hidden" id="lat" name="lat" value="{{ $shop->latitude }}" class="form-control">
                            </div>
                            <div class="form-group">
                                {{-- <label for="long" class="input-label">{{ \App\CPU\Translate('Longitude') }}</label> --}}
                                <input type="hidden" id="long" name="long" value="{{ $shop->longitude }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-12 mb-4">
                                    <label class="control-label">{{\App\CPU\translate('Youtube video link')}}</label>
                                    <small class="badge badge-soft-danger"> ( {{\App\CPU\translate('optional, please provide embed link not direct link')}}. )</small>
                                    <input type="text" value="{{$shop['video_url']}}" name="video_link"
                                        placeholder="{{\App\CPU\translate('EX')}} : https://www.youtube.com/embed/5R06LRdUCSE"
                                        class="form-control" required>
                                </div> --}}

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{\App\CPU\translate('outlet images')}}</label><small
                                            style="color: red">* ( {{\App\CPU\translate('ratio')}} 1:1 )</small>
                                    </div>
                                    <div class="row" id="thumbnail">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img style="width: 100%" height="auto"
                                                        onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{asset("storage/outlet")}}/{{$shop['image']}}"
                                                        alt="Product image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px">
                                @if($shop->request_status == 2)
                                    <button type="button" onclick="check()" class="btn btn-primary">{{\App\CPU\translate('Update & Publish')}}</button>
                                @else
                                    <button type="button" onclick="check()" class="btn btn-primary">{{\App\CPU\translate('Update')}}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{asset('assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{asset('assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap&v=weekly" defer></script>
    <script>
        $(document).ready(function(){
        var lat = {{ $shop->latitude }};
        var long = {{ $shop->longitude }};
        console.log('coor', lat, long)
        var myLatlng = new google.maps.LatLng(lat, long);
        var myOptions = {
        zoom: 15,
        center: myLatlng
        }
        map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
        infoWindow = new google.maps.InfoWindow();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const pos = {
                    lat: lat,
                    lng: long,
                };

                var label = 'Your location'

                var marker = new google.maps.Marker({
                    position: pos,
                    animation: google.maps.Animation.DROP,
                    draggable: true,
                    map: map,
                });

                 // Mengambil alamat on drag
                var geocoder = new google.maps.Geocoder();
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


        var thumbnail = '{{\App\CPU\ProductManager::product_image_path('outlet').'/'.$shop->image??asset('assets/back-end/img/400x400/img2.jpg')}}';

            $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-4',
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

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }

        $('input[name="colors_active"]').on('change', function () {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });

        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                //console.log($(this).val());
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="{{\App\CPU\translate('Choice Title') }}" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="{{\App\CPU\translate('Enter choice values') }}" data-role="tagsinput" onchange="update_sku()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        setTimeout(function () {
            $('.call-update-sku').on('change', function () {
                update_sku();
            });
        }, 2000)

        $('#colors-selector').on('change', function () {
            update_sku();
        });

        $('input[name="unit_price"]').on('keyup', function () {
            update_sku();
        });

        function update_sku() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('admin.product.sku-combination')}}',
                data: $('#product_form').serialize(),
                success: function (data) {
                    $('#sku_combination').html(data.view);
                    update_qty();
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category_id").val();
                let sub_category = $("#sub-category-select").attr("data-id");
                let sub_sub_category = $("#sub-sub-category-select").attr("data-id");
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + category + '&sub_category=' + sub_category, 'sub-category-select', 'select');
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + sub_category + '&sub_category=' + sub_sub_category, 'sub-sub-category-select', 'select');
            }, 100)
            // color select select2
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>

    <script>
        function check() {
            var formData = new FormData(document.getElementById('product_form'));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.outlet-update',$shop->id)}}',
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
                        toastr.success('Outlet updated successfully!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('#product_form').submit();
                    }
                }
            });
        };
    </script>

    <script>
        update_qty();

        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {

                $('input[name="current_stock"]').attr("readonly", true);
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }

        $('input[name^="qty_"]').on('keyup', function () {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            $('input[name="current_stock"]').val(total_qty);
        });
    </script>
@endpush
