@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product Edit'))

@push('css_or_js')
    <link href="{{asset('assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('admin.product.list', ['in_house', ''])}}">{{\App\CPU\translate('Services')}}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Edit')}}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form class="product-form" action="{{route('admin.product.update',$product->id)}}" method="post"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    enctype="multipart/form-data"
                    id="product_form">
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs mb-4">
                                @foreach(json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#"
                                           id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="card-body">
                            @foreach(json_decode($language) as $lang)
                                <?php
                                if (count($product['translations'])) {
                                    $translate = [];
                                    foreach ($product['translations'] as $t) {

                                        if ($t->locale == $lang && $t->key == "name") {
                                            $translate[$lang]['name'] = $t->value;
                                        }
                                        if ($t->locale == $lang && $t->key == "description") {
                                            $translate[$lang]['description'] = $t->value;
                                        }

                                    }
                                }
                                ?>
                                <div class="{{$lang != 'en'? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang}}_name">{{\App\CPU\translate('name')}}
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="name[]"
                                               id="{{$lang}}_name"
                                               value="{{$translate[$lang]['name']??$product['name']}}"
                                               class="form-control" placeholder="{{\App\CPU\translate('New Product')}}" required>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                    <div class="form-group pt-4">
                                        <label class="input-label">{{\App\CPU\translate('description')}}
                                            ({{strtoupper($lang)}})</label>
                                        <textarea name="description[]" style="display:none" class="textarea"
                                                  required>{!! $translate[$lang]['description']??$product['details'] !!}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h4>{{\App\CPU\translate('Product price & stock')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">{{\App\CPU\translate('Unit price')}}</label>
                                        <input type="number" min="0" step="0.01"
                                               placeholder="{{\App\CPU\translate('Unit price') }}"
                                               name="unit_price" class="form-control"
                                               value={{\App\CPU\Convert::default($product->unit_price)}} required>
                                    </div>
                                    <input type="hidden" min="0" value="0" step="0.01"
                                                name="purchase_price" value="{{\App\CPU\Convert::default($product->purchase_price)}}"  class="form-control" required>

                                    <div class="col-md-5">
                                        <label class="control-label">{{\App\CPU\translate('Tax')}}</label>
                                        <label class="badge badge-info">{{\App\CPU\translate('Percent')}} ( % )</label>
                                        <input type="number" min="0" value={{ $product->tax }} step="0.01"
                                               placeholder="{{\App\CPU\translate('Tax') }}" name="tax"
                                               class="form-control" required>
                                        <input name="tax_type" value="percent" style="display: none">
                                    </div>

                                    <div class="col-md-5">
                                        <label class="control-label">{{\App\CPU\translate('Discount')}}</label>
                                        <input type="number" min="0"
                                               value={{ $product->discount_type=='flat'?\App\CPU\Convert::default($product->discount): $product->discount}} step="0.01"
                                               placeholder="{{\App\CPU\translate('Discount') }}" name="discount"
                                               class="form-control" required>
                                    </div>
                                    <div class="col-md-2" style="padding-top: 30px;">
                                        <select
                                            style="width: 100%"
                                            class="js-example-basic-multiple js-states js-example-responsive demo-select2"
                                            name="discount_type">
                                            <option
                                                value="percent" {{$product['discount_type']=='percent'?'selected':''}}>{{\App\CPU\translate('Percent')}}</option>
                                            <option
                                                value="flat" {{$product['discount_type']=='flat'?'selected':''}}>{{\App\CPU\translate('Flat')}}</option>

                                        </select>
                                    </div>
                                    <div class="col-md-6" id="quantity">
                                        <label
                                            class="control-label">{{\App\CPU\translate('total')}} {{\App\CPU\translate('Quantity')}}</label>
                                        <input type="number" min="0" value={{ $product->current_stock }} step="1"
                                               placeholder="{{\App\CPU\translate('Quantity') }}"
                                               name="current_stock" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <br>
                        </div>
                    </div>

                    <div class="card mt-2 mb-2 rest-part">
                        <div class="card-header">
                            <h4>{{\App\CPU\translate('seo_section')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="control-label">{{\App\CPU\translate('Meta Title')}}</label>
                                    <input type="text" name="meta_title" value="{{$product['meta_title']}}" placeholder="" class="form-control">
                                </div>

                                <div class="col-md-8 mb-4">
                                    <label class="control-label">{{\App\CPU\translate('Meta Description')}}</label>
                                    <textarea rows="10" type="text" name="meta_description" class="form-control">{{$product['meta_description']}}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>{{\App\CPU\translate('Meta Image')}}</label>
                                    </div>
                                    <div class="border-dashed">
                                        <div class="row" id="meta_img">
                                            <div class="col-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img style="width: 100%" height="auto"
                                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                            src="{{asset("storage/product/meta")}}/{{$product['meta_image']}}"
                                                            alt="Meta image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{\App\CPU\translate('Upload product images')}}</label><small
                                            style="color: red">* ( {{\App\CPU\translate('ratio')}} 1:1 )</small>
                                    </div>
                                    <div class="p-2 border border-dashed" style="max-width:430px;">
                                        <div class="row" id="coba">
                                                <div class="col-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <img style="width: 100%" height="auto"
                                                                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                                src="{{asset("storage/product/$product->images")}}"
                                                                alt="Product image">
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{\App\CPU\translate('Upload thumbnail')}}</label><small
                                            style="color: red">* ( {{\App\CPU\translate('ratio')}} 1:1 )</small>
                                    </div>
                                    <div class="p-2 border border-dashed" style="max-width: 430px;">
                                        <div class="row" id="thumbnail">
                                            <div class="col-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img style="width: 100%" height="auto"
                                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                            src="{{asset("storage/product/thumbnail")}}/{{$product['thumbnail']}}"
                                                            alt="Product image">
                                                    </div>
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
                                @if($product->request_status == 2)
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
    <script>
        var imageCount = '{{\App\CPU\ProductManager::product_image_path('product').'/'.$product->images??asset('assets/back-end/img/400x400/img2.jpg')}}';
        var thumbnail = '{{\App\CPU\ProductManager::product_image_path('thumbnail').'/'.$product->thumbnail??asset('assets/back-end/img/400x400/img2.jpg')}}';
        $(function () {
                $("#coba").spartanMultiImagePicker({
                    fieldName: 'product',
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

            $("#thumbnail").spartanMultiImagePicker({
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

            $("#meta_img").spartanMultiImagePicker({
                fieldName: 'meta_image',
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
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var formData = new FormData(document.getElementById('product_form'));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.update',$product->id)}}',
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
                        toastr.success('product updated successfully!', {
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

    <script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        })
    </script>

    {{--ck editor--}}
    <script src="{{asset('/')}}vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
    {{--ck editor--}}
@endpush
