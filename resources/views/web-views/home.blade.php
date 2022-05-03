{{-- home.blade --}}

@extends('layouts.front-end.app')

@section('title','Welcome To '. $web_config['name']->value.' Home')

@push('css_or_js')
<meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<link rel="stylesheet" href="{{asset('assets/front-end')}}/css/home.css" />
<style>

#navbarCollapse > ul.navbar-nav.mega-nav.pr-2.pl-2.mr-2.d-none.d-xl-block > li > a {
        pointer-events: none;
    }
    #navbarCollapse > ul.navbar-nav.mega-nav.pr-2.pl-2.mr-2.d-none.d-xl-block > li > ul {
        display: block !important;
    }
  .media {
    background: white;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
  }

  .cz-countdown-days {
            color: white !important;
            background-color: {{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px;
            margin-right: 3px !important;
        }

        .cz-countdown-hours {
            color: white !important;
            background-color: {{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px;
            margin-right: 3px !important;
        }

        .cz-countdown-minutes {
            color: white !important;
            background-color: {{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px;
            margin-right: 3px !important;
        }

        .cz-countdown-seconds {
            color: {{$web_config['primary_color']}};
            border: 1px solid{{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px !important;
        }
  .flash_deal_product_details .flash-product-price {
    font-weight: 700;
    font-size: 18px;

    color: {
        {
        $web_config['primary_color']
      }
    }

    ;
  }

  .featured_deal_left {
    height: 130px;

    background: {
        {
        $web_config['primary_color']
      }
    }

    0% 0% no-repeat padding-box;
    padding: 10px 100px;
    text-align: center;
  }

  .featured_deal {
    min-height: 130px;

  }

  .category_div:hover {
    color: {
        {
        $web_config['secondary_color']
      }
    }

    ;
  }

     .deal_of_the_day {
            /* filter: grayscale(0.5); */
            opacity: .8;
            background: {{$web_config['secondary_color']}};
            border-radius: 3px;
        }

  .deal-title {
    font-size: 12px;

  }

  .for-flash-deal-img img {
    max-width: none;
  }

  @media (max-width: 375px) {
    .cz-countdown {
      display: flex !important;

    }

    .cz-countdown .cz-countdown-seconds {

      margin-top: -5px !important;
    }

    .for-feature-title {
      font-size: 20px !important;
    }
  }

  @media (max-width: 600px) {
    .flash_deal_title {
      font-weight: 600;
      font-size: 18px;
      text-transform: uppercase;
    }

    .cz-countdown .cz-countdown-value {
      font-family: "Roboto", sans-serif;
      font-size: 11px !important;
      font-weight: 700 !important;
    }

    .featured_deal {
      opacity: 1 !important;
    }

    .cz-countdown {
      display: inline-block;
      flex-wrap: wrap;
      font-weight: normal;
      margin-top: 4px;
      font-size: smaller;
    }

    .view-btn-div-f {

      margin-top: 6px;
      float: right;
    }

    .view-btn-div {
      float: right;
    }

    .viw-btn-a {
      font-size: 10px;
      font-weight: 600;
    }


    .for-mobile {
      display: none;
    }

    .featured_for_mobile {
      max-width: 100%;
      margin-top: 20px;
      margin-bottom: 20px;
    }
  }

  @media (max-width: 360px) {
    .featured_for_mobile {
      max-width: 100%;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .featured_deal {
      opacity: 1 !important;
    }
  }

  @media (max-width: 375px) {
    .featured_for_mobile {
      max-width: 100%;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .featured_deal {
      opacity: 1 !important;
    }
  }


  @media (max-width: 992px) {
    .navbar-collapse {
      position: fixed;
      top: 69px;
      left: 0;
      padding-left: 15px;
      padding-right: 15px;
      padding-bottom: 15px;
      width: 89%;
      height: 100%;
      z-index: 99;
    }

    .navbar-collapse.collapsing {
      left: -75%;
      transition: height 0s ease;
    }

    .navbar-collapse.show {
      left: 0;
      background-color: #fff;
      transition: left 300ms ease-in-out;
    }

    .navbar-toggler.collapsed~.navbar-collapse {
      transition: left 500ms ease-in-out;
    }

    @media (max-width:768px) {
      .nav-item.dropdown.ml-auto {
        margin-left: 0px !important;
      }

      .timer {
        margin: 0 auto;
      }

      .timer .view_all .px-2 .cz-countdown {
        margin-left: 0 !important;
      }
    }
  }

  @media (min-width: 768px) {
    .displayTab {
      display: block !important;
    }
  }

  @media (max-width: 800px) {
    .for-tab-view-img {
      width: 40%;
    }

    .for-tab-view-img {
      width: 105px;
    }

    .widget-title {
      font-size: 19px !important;
    }
  }

  .featured_deal_carosel .carousel-inner {
    width: 100% !important;
  }

  .badge-style2 {
    color: black !important;
    background: transparent !important;
    font-size: 11px;
  }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
  integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
  integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush

@section('content')
<!-- Hero (Banners + Slider)-->
<section class="bg-transparent mb-3">
  <div class="container">
    <div class="row ">
      <div class="col-12">
        @include('web-views.partials._home-top-slider')
      </div>
    </div>
  </div>
</section>

  {{--categries--}}
    <section class="container rtl">
        <!-- Heading-->
        <div class="section-header">
            <div class="feature_header">
                <span>{{ \App\CPU\translate('categories')}}</span>
            </div>
            <div>
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                   href="{{route('categories')}}">{{ \App\CPU\translate('lihat_lihat')}}
                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                </a>
            </div>
        </div>

        <div class="mt-2 mb-3 brand-slider">
            <div class="owl-carousel owl-theme " id="category-slider">
                @foreach($categories as $category)
                    <div class="category_div" style="height: 132px; width: 100%;">
                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                            <img style="vertical-align: middle; padding: 16%;height: 98px"
                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 src="{{asset("storage/category/$category->icon")}}"
                                 alt="{{$category->name}}">
                            <p class="text-center small"
                               style="margin-top: -20px">{{Str::limit($category->name, 17)}}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

{{--flash deal--}}
@php($flash_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'flash_deal'])->whereDate('start_date','
<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->first())

@if (isset($country))
    <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header fd rtl row justify-content-between">
          <div class="col-md-2" style="padding-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: 0">
            <div class="d-inline-flex displayTab">
              <span class="flash_deal_title ">
                {{$flash_deals['title']}}
              </span>
            </div>
          </div>
          <div class="col-lg-10 col-md-8 col-sm-10 col-12 timer" style="padding-{{Session::get('direction') === " rtl"
            ? 'left' : 'right' }}: 0">
            <div class="view_all view-btn-div-f w-100" style="justify-content: space-between !important">
              <div class="px-2">
                <span class="cz-countdown" style="margin-left: -6vw;"
                  data-countdown="{{isset($flash_deals)?date('m/d/Y',strtotime($flash_deals['end_date'])):''}} 11:59:00 PM">
                  <span class="cz-countdown-days">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-hours">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-minutes">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-seconds">
                    <span class="cz-countdown-value"></span>
                  </span>
                </span>
              </div>
              <div class="">
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                  href="{{route('flash-deals',[isset($flash_deals)?$flash_deals['id']:0])}}">{{
                  \App\CPU\translate('view_all')}}
                  <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'
                    }}"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
          @if (count($flash->products[0]->product) > 0)
        <div class="owl-carousel owl-theme" id="flash-deal-slider">
          @foreach($flash->products[0]->product as $key=>$deal)
          @if( $deal->product)
          @php($overallRating = \App\CPU\ProductManager::get_overall_rating(isset($deal)?$deal->product->reviews:null))
          <div class="flash_deal_product rtl" style="cursor: pointer;"
            onclick="location.href='{{route('product',$deal->product->slug)}}'">
            @if($deal->product->discount > 0)
            <div class=" discount-top-f">
              <span class="for-discoutn-value">
                @if ($deal->product->discount_type == 'percent')
                {{round($deal->product->discount)}}%
                @elseif($deal->product->discount_type =='flat')
                {{\App\CPU\Helpers::currency_converter($deal->product->discount)}}
                @endif OFF
              </span>
            </div>
            @else
            <div class="">
              <span class="for-discoutn-value-null"></span>
            </div>
            @endif
            <div class=" d-flex">
              <div class="d-flex align-items-center justify-content-center" style="min-width: 110px">
                <img style="height: 130px!important;"
                  src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal->product['thumbnail']}}"
                  onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" />
              </div>
              <div class="flash_deal_product_details pl-2 pr-1 d-flex align-items-center">
                <div>
                  <h6 class="flash-product-title">
                    {{$deal->product['name']}}
                  </h6>
                  <div class="flash-product-price">
                    {{\App\CPU\Helpers::currency_converter($deal->product->unit_price-\App\CPU\Helpers::get_product_discount($deal->product,$deal->product->unit_price))}}
                    @if($deal->product->discount > 0)
                    <strike style="font-size: 12px!important;color: grey!important;">
                      {{\App\CPU\Helpers::currency_converter($deal->product->unit_price)}}
                    </strike>
                    @endif
                  </div>
                  <h6 class="flash-product-review">
                    @for($inc=0;$inc<5;$inc++) @if($inc<$overallRating[0]) <i class="sr-star czi-star-filled active">
                      </i>
                      @else
                      <i class="sr-star czi-star"></i>
                      @endif
                      @endfor
                      <label class="badge-style2">
                        ( {{$deal->product->reviews()->count()}} )
                      </label>
                  </h6>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        @else
                <h4 class="text-center" style="color: #828584;">Not Availbale in this country</h4>
            @endif
      </div>
    </div>
  </div>
@endif

  @if (isset($flash_deals) && empty($country))
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header fd rtl row justify-content-between">
          <div class="col-md-2" style="padding-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: 0">
            <div class="d-inline-flex displayTab">
              <span class="flash_deal_title ">
                {{$flash_deals['title']}}
              </span>
            </div>
          </div>
          <div class="col-lg-10 col-md-8 col-sm-10 col-12 timer" style="padding-{{Session::get('direction') === " rtl"
            ? 'left' : 'right' }}: 0">
            <div class="view_all view-btn-div-f w-100" style="justify-content: space-between !important">
              <div class="px-2">
                <span class="cz-countdown" style="margin-left: -6vw;"
                  data-countdown="{{isset($flash_deals)?date('m/d/Y',strtotime($flash_deals['end_date'])):''}} 11:59:00 PM">
                  <span class="cz-countdown-days">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-hours">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-minutes">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-seconds">
                    <span class="cz-countdown-value"></span>
                  </span>
                </span>
              </div>
              <div class="">
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                  href="{{route('flash-deals',[isset($flash_deals)?$flash_deals['id']:0])}}">{{
                  \App\CPU\translate('view_all')}}
                  <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'
                    }}"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="owl-carousel owl-theme" id="flash-deal-slider">
          @foreach($flash_deals->products as $key=>$deal)
          @if( $deal->product)
          @php($overallRating = \App\CPU\ProductManager::get_overall_rating(isset($deal)?$deal->product->reviews:null))
          <div class="flash_deal_product rtl" style="cursor: pointer;"
            onclick="location.href='{{route('product',$deal->product->slug)}}'">
            @if($deal->product->discount > 0)
            <div class=" discount-top-f">
              <span class="for-discoutn-value">
                @if ($deal->product->discount_type == 'percent')
                {{round($deal->product->discount)}}%
                @elseif($deal->product->discount_type =='flat')
                {{\App\CPU\Helpers::currency_converter($deal->product->discount)}}
                @endif OFF
              </span>
            </div>
            @else
            <div class="">
              <span class="for-discoutn-value-null"></span>
            </div>
            @endif
            <div class=" d-flex">
              <div class="d-flex align-items-center justify-content-center" style="min-width: 110px">
                <img style="height: 130px!important;"
                  src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal->product['thumbnail']}}"
                  onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" />
              </div>
              <div class="flash_deal_product_details pl-2 pr-1 d-flex align-items-center">
                <div>
                  <h6 class="flash-product-title">
                    {{$deal->product['name']}}
                  </h6>
                  <div class="flash-product-price">
                    {{\App\CPU\Helpers::currency_converter($deal->product->unit_price-\App\CPU\Helpers::get_product_discount($deal->product,$deal->product->unit_price))}}
                    @if($deal->product->discount > 0)
                    <strike style="font-size: 12px!important;color: grey!important;">
                      {{\App\CPU\Helpers::currency_converter($deal->product->unit_price)}}
                    </strike>
                    @endif
                  </div>
                  <h6 class="flash-product-review">
                    @for($inc=0;$inc<5;$inc++) @if($inc<$overallRating[0]) <i class="sr-star czi-star-filled active">
                      </i>
                      @else
                      <i class="sr-star czi-star"></i>
                      @endif
                      @endfor
                      <label class="badge-style2">
                        ( {{$deal->product->reviews()->count()}} )
                      </label>
                  </h6>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endif

  {{--brands--}}
    <section class="container rtl">
        <!-- Heading-->
        <div class="section-header">
            <div class="feature_header" style="color: black">
                <span> {{\App\CPU\translate('brands')}}</span>
            </div>
            <div>
                <a class="btn btn-outline-accent btn-sm viw-btn-a" href="{{route('brands')}}">
                    {{ \App\CPU\translate('view_all')}}
                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                </a>
            </div>
        </div>
    {{--<hr class="view_border">--}}
    <!-- Grid-->
        <div class="mt-2 mb-3 brand-slider">
            <div class="owl-carousel owl-theme" id="brands-slider">
                @foreach($brands as $brand)
                    <div class="text-center">
                        <a href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}">
                            <div class="brand_div d-flex align-items-center justify-content-center"
                                 style="height:98px">
                                <img style="max-height: 98px; max-width: 98px"
                                    onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset("storage/brand/$brand->image")}}" alt="{{$brand->name}}">
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


  {{--deal of the day--}}
  <div class="new-section mb-5">
    <div class="container">
      <div class="row">
        {{-- Deal of the day/Recommended Product --}}
        <div class="col col-md-3 col-sm-6 col-12 day-off">
        <div class="deal_of_the_day">
                    @if(isset($deal_of_the_day))
                        <h1 style="color: white"> {{ \App\CPU\translate('deal_of_the_day') }}</h1>
                        <center>
                            <strong style="font-size: 21px!important;color: {{$web_config['primary_color']}}">
                                {{$deal_of_the_day->discount_type=='amount'?\App\CPU\Helpers::currency_converter($deal_of_the_day->discount):$deal_of_the_day->discount.' % '}}
                                {{\App\CPU\translate('off')}}
                            </strong>
                        </center>
                        <div class="d-flex justify-content-center align-items-center" style="padding-top: 37px">
                            <img style="height: 206px;"
                                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal_of_the_day->product['thumbnail']}}"
                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 alt="">
                        </div>
                        <div style="text-align: center; padding-top: 26px;">
                            <h5 style="font-weight: 600; color: {{$web_config['primary_color']}}">
                                {{\Illuminate\Support\Str::limit($deal_of_the_day->product['name'],40)}}
                            </h5>
                            <span class="text-accent">
                                {{\App\CPU\Helpers::currency_converter(
                                    $deal_of_the_day->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price))
                                )}}
                            </span>
                            @if($deal_of_the_day->product->discount > 0)
                                <strike style="font-size: 12px!important;color: grey!important;">
                                    {{\App\CPU\Helpers::currency_converter($deal_of_the_day->product->unit_price)}}
                                </strike>
                            @endif

                        </div>
                        <div class="pt-3 pb-2" style="text-align: center;">
                            <button class="buy_btn"
                                    onclick="location.href='{{route('product',$deal_of_the_day->product->slug)}}'">{{\App\CPU\translate('buy_now')}}
                            </button>
                        </div>
                    @else
                        @php($product=\App\Model\Product::active()->inRandomOrder()->first())
                        @if(isset($product))
                            <h1 style="color: white"> {{ \App\CPU\translate('recommended_product') }}</h1>
                            <div class="d-flex justify-content-center align-items-center" style="padding-top: 55px">
                                <img style="height: 206px;"
                                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                     onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                     alt="">
                            </div>
                            <div style="text-align: center; padding-top: 60px;" class="pb-2">
                                <button class="buy_btn" onclick="location.href='{{route('product',$product->slug)}}'">
                                    {{\App\CPU\translate('buy_now')}}
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
                  <div class="container mt-2">
            <div class="row p-0">
              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center mobile-padding">
                <img style="height: 29px;" src="{{asset("/assets/front-end/png/delivery.png")}}" alt="">
                <div class="deal-title">3 Days <br><span>Fast Delivery</span></div>
              </div>

              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center">
                <img style="height: 29px;" src="{{asset("/assets/front-end/png/money.png")}}" alt="">
                <div class="deal-title">Money Back <br><span>Gurrantey</span></div>
              </div>
              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center">
                <img style="height: 29px;" src="{{asset("/assets/front-end/png/Genuine.png")}}" alt="">
                <div class="deal-title">100% Genuine<br><span>Product</span></div>
              </div>
              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center">
                <img style="height: 29px;" src="{{asset("/assets/front-end/png/Payment.png")}}" alt="">
                <div class="deal-title">Authentic<br><span>Payment</span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-md-9 col-sm-6 col-12">
          <div class="row">
            {{-- featured --}}
            <div class="col col-md-6 col-sm-6 col-12 products">
              @if(count($featured_products) > 0)
              <div class="container rtl card p-2 h-100" style="box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
-webkit-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
-moz-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);">
                <!-- Heading-->
                <div class="section-header">
                  <div class="feature_header">
                    <span class="for-feature-title">{{ \App\CPU\translate('featured_products')}}</span>
                  </div>
                  <div>
                    <a class="btn btn-outline-accent btn-sm viw-btn-a"
                      href="{{route('products',['data_from'=>'featured','page'=>1])}}">
                      {{ \App\CPU\translate('view_all')}}
                      <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1'
                        : 'right ml-1 mr-n1' }}"></i>
                    </a>
                  </div>
                </div>
                {{--
                <hr class="view_border">--}}
                <!-- Grid-->
                <div class="row mt-2 mb-3">
                  @foreach($featured_products->slice(0, 6) as $product)
                  <div class="col-lg-4 col-md-4 col-sm-6 col-6 mb-2">
                    @include('web-views.partials._single_product_featured',['product'=>$product])
                    {{--
                    <hr class="d-sm-none">--}}
                  </div>
                  @endforeach
                </div>
              </div>
              @endif
            </div>


            {{-- Latest products --}}
            <div class="col col-md-6 col-sm-6 col-12 latest card p-2" style="box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -webkit-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -moz-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);">
              <div class="container rtl">
                <div class="section-header">
                  <div class="feature_header">
                    <span class="for-feature-title">{{ \App\CPU\translate('latest_products')}}</span>
                  </div>
                  <div>
                    <a class="btn btn-outline-accent btn-sm viw-btn-a"
                      href="{{route('products',['data_from'=>'latest'])}}">
                      {{ \App\CPU\translate('view_all')}}
                      <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1'
                        : 'right ml-1 mr-n1' }}"></i>
                    </a>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="row mt-2 mb-3">
                  @foreach($latest_products->slice(0, 6) as $product)
                  <div class="col-lg-4 col-md-4 col-sm-6 col-6 mb-2">
                    @include('web-views.partials._single_product_featured',['product'=>$product])
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

<!-- new section2 -->

   <section class="container pb-4 pb-md-5 rtl">
        <div class="row">
            <!-- Bestsellers-->
            <div class="col-12 col-sm-6 col-md-4 mb-2 py-3">
                <div class="container h-100 card p-2" style="box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -webkit-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -moz-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);">
                    <div class="widget h-100">
                        <div class="d-flex justify-content-between section-header">
                            <div class="feature_header">
                                <span class="for-feature-title">{{ \App\CPU\translate('best_sellings')}}</span>
                            </div>
                            <div>
                                <a class="btn btn-outline-accent p-2 px-2" style="width: unset;"
                                    href="{{route('products',['data_from'=>'best-selling','page'=>1])}}">{{
                                    \App\CPU\translate('view_all')}}
                                    <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1'
                                        : 'right ml-1 mr-n1' }}"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($bestSellProduct as $key=>$bestSell)
                            @if($bestSell->product && $key<4) <div class="col col-md-4 col-sm-2">
                                <div class="media d-flex flex-column pt-2 pb-2 mb-1"
                                    data-href="{{route('product',$bestSell->product->slug)}}" style="height:215px">
                                    <div class="card-header" style="min-height: 127px; max-height: 135px; width: 100%;">
                                        <a class="d-block text-center {{Session::get('direction') === " rtl" ? 'ml-2'
                                            : 'mr-2' }}" href="{{route('product',$bestSell->product->slug)}}">
                                            <img style="height: 100%; width: 100%; max-height: 120px; max-width: 95px"
                                                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$bestSell->product['thumbnail']}}"
                                                alt="Product" />
                                        </a>
                                    </div>
                                    <div class="media-body w-100 h-100 text-center">
                                        <h6 class="widget-product-title">
                                            <a class="ptr"
                                                href="{{route('product',$product->slug)}}">{{substr($bestSell->product['name'],0,30)}}
                                                {{strlen($bestSell->product['name'])>30?'...':''}}</a>
                                        </h6>
                                        <div class="widget-product-meta">
                                            <span class="text-accent d-flex flex-column">
                                                {{\App\CPU\Helpers::currency_converter(
                                                $bestSell->product->unit_price-(\App\CPU\Helpers::get_product_discount($bestSell->product,$bestSell->product->unit_price))
                                                )}}

                                                @if($bestSell->product->discount > 0)
                                                <strike style="font-size: 12px!important;color: grey!important;">
                                                    {{\App\CPU\Helpers::currency_converter($bestSell->product->unit_price)}}
                                                </strike>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start w-100"
                                            style="position: absolute;bottom: -15px;left: 5px;z-index: 1;">
                                            <div class="flag">
                                                <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
                                                    width="20"
                                                    src="{{asset('assets/front-end')}}/img/flags/{{ strtolower($product->country)  }}.png"
                                                    alt="Eng">
                                            </div>
                                            @php($c_name = App\Country::where('country', $product->country)->get())
                                            <span style="font-size: 13px; color: #616166; line-height: 2;">{{
                                                $c_name[0]->country_name }}</span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- New arrivals-->
        <div class="col-12 col-sm-6 col-md-4 mb-2 py-3">
            <div class="container h-100 card p-2" style="box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -webkit-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -moz-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);">
                <div class="widget h-100">
                    <div class="d-flex justify-content-between">
                        <div class="feature_header">
                            <span class="for-feature-title">{{ \App\CPU\translate('new_arrival')}}</span>
                        </div>
                        <div>
                            <a class="btn btn-outline-accent p-2 px-2" style="width: unset;"
                                href="{{route('products',['data_from'=>'best-selling','page'=>1])}}">{{
                                \App\CPU\translate('view_all')}}
                                <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1'
                                    : 'right ml-1 mr-n1' }}"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($latest_products as $key=>$product)
                        @if($key<4) <div class="col col-md-4 col-sm-2 mb-2">
                            <div class="media d-flex flex-column pt-2 pb-2 mb-1" style="height:215px"
                                data-href="{{route('product',$product->slug)}}">
                                <div class="card-header" style="min-height: 127px; max-height: 135px; width: 100%;">
                                    <a class="d-block text-center {{Session::get('direction') === " rtl" ? 'ml-2'
                                        : 'mr-2' }}" href="{{route('product',$product->slug)}}">
                                        <img style="height: 100%; width: 100%; max-height: 120px; max-width: 95px"
                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                            alt="Product" />
                                    </a>
                                </div>

                                <div class="media-body w-100 h-100 text-center">
                                    <h6 class="widget-product-title">
                                        <a class="ptr" href="{{route('product',$product->slug)}}">
                                            {{substr($product['name'],0,30)}} {{strlen($product['name'])>30?'...':''}}
                                        </a>
                                    </h6>
                                    <div class="widget-product-meta">
                                        <span class="text-accent d-flex flex-column">
                                            {{\App\CPU\Helpers::currency_converter(
                                            $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                                            )}}
                                            @if($product->discount > 0)
                                            <strike style="font-size: 12px!important;color: grey!important;">
                                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                            </strike>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-start w-100"
                                        style="position: absolute;bottom: -15px;left: 5px;z-index: 1;">
                                        <div class="flag">
                                            <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
                                                width="20"
                                                src="{{asset('assets/front-end')}}/img/flags/{{ strtolower($product->country)  }}.png"
                                                alt="Eng">
                                        </div>
                                        @php($c_name = App\Country::where('country', $product->country)->get())
                                            <span style="font-size: 13px; color: #616166; line-height: 2;">{{
                                                $c_name[0]->country_name }}</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        </div>
        <!-- Top rated-->
        <div class="col-12 col-sm-6 col-md-4 mb-2 py-3 ">
            <div class="container h-100 card p-2" style="box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -webkit-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
      -moz-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);">
                <div class="widget h-100">
                    <div class="d-flex justify-content-between">
                        <div class="feature_header">
                            <span class="for-feature-title">{{ \App\CPU\translate('top_rated')}}</span>
                        </div>
                        <div>
                            <a class="btn btn-outline-accent p-2 px-2" style="width: unset;"
                                href="{{route('products',['data_from'=>'best-selling','page'=>1])}}">{{
                                \App\CPU\translate('view_all')}}
                                <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1'
                                    : 'right ml-1 mr-n1' }}"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($topRated as $key=>$top)
                        @if($top->product && $key<4) <div class="col col-md-4 col-sm-2 mb-2">
                            <div class="media d-flex flex-column pt-2 pb-2 mb-1" style="height:215px"
                                data-href="{{route('product',$product->slug)}}">
                                <div class="card-header" style="min-height: 127px; max-height: 135px; width: 100%;">
                                    <a class="d-block text-center {{Session::get('direction') === " rtl" ? 'ml-2'
                                        : 'mr-2' }}" href="{{route('product',$top->product->slug)}}"
                                        style="min-height: 127px; max-height: 135px">
                                        <img style="height: 100%; width: 100%; max-height: 120px; max-width: 95px"
                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$top->product['thumbnail']}}"
                                            alt="Product" />
                                    </a>
                                </div>

                                <div class="media-body w-100 h-100 text-center">
                                    <h6 class="widget-product-title">
                                        <a class="ptr" href="{{route('product',$top->product->slug)}}">
                                            {{substr($top->product['name'],0,30)}}
                                            {{strlen($top->product['name'])>30?'...':''}}
                                        </a>
                                    </h6>
                                    <div class="widget-product-meta">
                                        <span class="text-accent d-flex flex-column">
                                            {{\App\CPU\Helpers::currency_converter(
                                            $top->product->unit_price-(\App\CPU\Helpers::get_product_discount($top->product,$top->product->unit_price))
                                            )}}

                                            @if($top->product->discount > 0)
                                            <strike style="font-size: 12px!important;color: grey!important;">
                                                {{\App\CPU\Helpers::currency_converter($top->product->unit_price)}}
                                            </strike>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-start w-100"
                                        style="position: absolute;bottom: -15px;left: 5px;z-index: 1;">
                                        <div class="flag">
                                            <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
                                                width="20"
                                                src="{{asset('assets/front-end')}}/img/flags/{{ strtolower($product->country)  }}.png"
                                                alt="Eng">
                                        </div>
                                        @php($c_name = App\Country::where('country', $top->product->country)->get())
                                            <span style="font-size: 13px; color: #616166; line-height: 2;">{{
                                                $c_name[0]->country_name }}</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        </div>
        </div>
    </section>

  {{--deal of the day--}}
  <!-- <div class="container rtl">
        <div class="row">
            {{-- Deal of the day/Recommended Product --}}
            <div class="col-xl-3 col-md-4 pb-4 mt-3">
                <div class="deal_of_the_day">
                    @if(isset($deal_of_the_day))
                        <h1 style="color: white"> {{ \App\CPU\translate('deal_of_the_day') }}</h1>
                        <center>
                            <strong style="font-size: 21px!important;color: {{$web_config['primary_color']}}">
                                {{$deal_of_the_day->discount_type=='amount'?\App\CPU\Helpers::currency_converter($deal_of_the_day->discount):$deal_of_the_day->discount.' % '}}
                                OFF
                            </strong>
                        </center>
                        <div class="d-flex justify-content-center align-items-center" style="padding-top: 37px">
                            <img style="height: 206px;"
                                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal_of_the_day->product['thumbnail']}}"
                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 alt="">
                        </div>
                        <div style="text-align: center; padding-top: 26px;">
                            <h5 style="font-weight: 600; color: {{$web_config['primary_color']}}">
                                {{substr($deal_of_the_day->product['name'],0,40)}} {{strlen($deal_of_the_day->product['name'])>40?'...':''}}
                            </h5>
                            <span class="text-accent">
                                {{\App\CPU\Helpers::currency_converter(
                                    $deal_of_the_day->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price))
                                )}}
                            </span>
                            @if($deal_of_the_day->product->discount > 0)
                                <strike style="font-size: 12px!important;color: grey!important;">
                                    {{\App\CPU\Helpers::currency_converter($deal_of_the_day->product->unit_price)}}
                                </strike>
                            @endif

                        </div>
                        <div class="pt-3 pb-2" style="text-align: center;">
                            <button class="buy_btn"
                                    onclick="location.href='{{route('product',$deal_of_the_day->product->slug)}}'">{{\App\CPU\translate('buy_now')}}
                            </button>
                        </div>
                    @else
                        @php($product=\App\Model\Product::active()->inRandomOrder()->first())
                        @if(isset($product))
                            <h1 style="color: white"> {{ \App\CPU\translate('recommended_product') }}</h1>
                            <div class="d-flex justify-content-center align-items-center" style="padding-top: 55px">
                                <img style="height: 206px;"
                                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                     onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                     alt="">
                            </div>
                            <div style="text-align: center; padding-top: 60px;" class="pb-2">
                                <button class="buy_btn" onclick="location.href='{{route('product',$product->slug)}}'">
                                    {{\App\CPU\translate('buy_now')}}
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="container mt-2">
                    <div class="row p-0">
                        <div class="col-md-3 p-0 text-center mobile-padding">
                            <img style="height: 29px;" src="{{asset("assets/front-end/png/delivery.png")}}" alt="">
                            <div class="deal-title">3 Days <br><span>Fast Delivery</span></div>
                        </div>

                        <div class="col-md-3 p-0 text-center">
                            <img style="height: 29px;" src="{{asset("assets/front-end/png/money.png")}}" alt="">
                            <div class="deal-title">Money Back <br><span>Gurrantey</span></div>
                        </div>
                        <div class="col-md-3 p-0 text-center">
                            <img style="height: 29px;" src="{{asset("assets/front-end/png/Genuine.png")}}" alt="">
                            <div class="deal-title">100% Genuine<br><span>Product</span></div>
                        </div>
                        <div class="col-md-3 p-0 text-center">
                            <img style="height: 29px;" src="{{asset("assets/front-end/png/Payment.png")}}" alt="">
                            <div class="deal-title">Authentic<br><span>Payment</span></div>
                        </div>
                    </div>
                </div>
            </div> -->


  {{-- Latest products --}}
  <!-- <div class="col-xl-9 col-md-8">
                <div class="section-header">
                    <div class="feature_header">
                        <span class="for-feature-title">{{ \App\CPU\translate('latest_products')}}</span>
                    </div>
                    <div>
                        <a class="btn btn-outline-accent btn-sm viw-btn-a"
                           href="{{route('products',['data_from'=>'latest'])}}">
                            {{ \App\CPU\translate('view_all')}}
                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                        </a>
                    </div>
                </div>

                <div class="row mt-2 mb-3">
                    @foreach($latest_products as $product)
                        <div class="col-xl-3 col-sm-4 col-6 mb-2">
                            @include('web-views.partials._single-product',['product'=>$product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> -->



  <!-- Products grid (featured products)-->
  <!-- @if(count($featured_products) > 0) -->
  <!--  <section class="container rtl">
            <!-- Heading-->
  <!-- <div class="section-header">
                <div class="feature_header">
                    <span class="for-feature-title">{{ \App\CPU\translate('featured_products')}}</span>
                </div>
                <div>
                    <a class="btn btn-outline-accent btn-sm viw-btn-a"
                       href="{{route('products',['data_from'=>'featured','page'=>1])}}">
                        {{ \App\CPU\translate('view_all')}}
                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                    </a>
                </div>
            </div>
        {{--<hr class="view_border">--}}
        <!-- Grid-->
  <!-- <div class="row mt-2 mb-3">
                @foreach($featured_products as $product)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                        @include('web-views.partials._single-product',['product'=>$product])
                        {{--<hr class="d-sm-none">--}}
                    </div>
                @endforeach
            </div>
        </section>
    @endif -->

  {{--featured deal--}}
  @php($featured_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'feature_deal'])->first())

  @if(isset($featured_deals))
  <section class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="featured_deal">
          <div class="row">
            <div class="col-xl-3 col-md-4 rtl">
              <div class="d-flex align-items-center justify-content-center featured_deal_left">
                <h1 class="featured_deal_title">{{ \App\CPU\translate('featured_deal')}}</h1>
              </div>
            </div>
            <div class="col-xl-9 col-md-8">
              <div class="owl-carousel owl-theme" id="web-feature-deal-slider">
                @foreach($featured_deals->products as $key=>$product)
                @php($product=$product->product)
                <div class="d-flex  align-items-center justify-content-center"
                  style="height: 129px;border: 1px solid #c5bfbf54;border-radius: 5px; background: white">
                  <div class="featured_deal_product d-flex align-items-center justify-content-between">
                    <div class="row">
                      <div class="col-4">
                        <div class="featured_product_pic mt-3" style=" text-align: center;">
                          <a href="{{route('product',$product->slug)}}" class="image_center">
                            <img
                              src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                              onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                              class="d-block w-100" alt="...">
                          </a>
                        </div>
                      </div>
                      <div class="col-8">
                        <div class="featured_product_details">
                          <h3 class="featured_product-title mt-2">
                            <a class="ptr" href="{{route('product',$product->slug)}}">
                              {{$product['name']}}
                            </a>
                          </h3>
                          <div class="featured_product-price">
                            <span class="text-accent ptp">
                              {{\App\CPU\Helpers::currency_converter(
                              $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                              )}}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif


  {{--categries--}}
  <!-- <section class="container rtl"> -->
  <!-- Heading-->
  <!--  <div class="section-header">
            <div class="feature_header">
                <span>{{ \App\CPU\translate('categories')}}</span>
            </div>
            <div>
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                   href="{{route('categories')}}">{{ \App\CPU\translate('view_all')}}
                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                </a>
            </div>
        </div>

        <div class="mt-2 mb-3 brand-slider">
            <div class="owl-carousel owl-theme " id="category-slider">
                @foreach($categories as $category)
                    <div class="category_div" style="height: 132px; width: 100%;">
                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                            <img style="vertical-align: middle; padding: 16%"
                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 src="{{asset("storage/category/$category->icon")}}"
                                 alt="{{$category->name}}">
                            <p class="text-center small"
                               style="margin-top: -20px">{{Str::limit($category->name, 17)}}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section> -->

  <!-- top sellers -->
  @if(count($top_sellers) > 0)
  <section class="container rtl">
    <!-- Heading-->
    <div class="section-header">
      <div class="feature_header">
        <span>{{ \App\CPU\translate('sellers')}}</span>
      </div>
      <div>
        <a class="btn btn-outline-accent btn-sm viw-btn-a" href="{{route('sellers')}}">{{
          \App\CPU\translate('view_all')}}
          <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
        </a>
      </div>
    </div>
    <!-- top seller Grid-->
    <div class="mt-2 mb-3 brand-slider">
      <div class="owl-carousel owl-theme" id="top-seller-slider">
        @foreach($top_sellers as $seller)
        @if($seller->shop)
        <div style="height: 100px; padding: 2%; background: white;border-radius: 5px">
          <center>
            <a href="{{route('shopView',['id'=>$seller['id']])}}">
              <img style="vertical-align: middle; padding: 2%;width:75px;height: 75px;border-radius: 50%"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{asset("
                storage/shop")}}/{{$seller->shop->image}}"
              alt="{{$seller->name}}">
              <p class="text-center small font-weight-bold">{{Str::limit($seller->name, 14)}}</p>
            </a>
          </center>
           <div class="d-flex justify-content-start w-100"
                        style="position: absolute;bottom: 10px;left: 10px;z-index: 1;">
                        <div class="flag d-flex align-items-center">
                            <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                src="{{asset('assets/front-end')}}/img/flags/{{ strtolower($seller->country)  }}.png"
                                alt="Eng" style="width: 20px">
                        </div>
                        @php($c_name = App\Country::where('country', $seller->country)->get())
                        <span style="font-size: 13px; color: #616166;">{{ $c_name[0]->country_name
                            }}</span>
                    </div>
        </div>
        @endif
        @endforeach
      </div>
    </div>

  </section>
  @endif

  {{-- Categorized product --}}
  @foreach($home_categories as $category)
  @if(App\CPU\CategoryManager::products($category['id'])->count()>0)
  <section class="container rtl">
    <!-- Heading-->
    <div class="section-header">
      <div class="feature_header">
        <span class="for-feature-title">{{$category['name']}}</span>
      </div>
      <div>
        <a class="btn btn-outline-accent btn-sm viw-btn-a"
          href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
          {{ \App\CPU\translate('view_all')}}
          <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
        </a>
      </div>
    </div>

    <div class="row mt-2 mb-3">
            @foreach(\App\CPU\CategoryManager::products($category['id']) as $key=>$product)
            @if($key<12) <div class="col-xl-2 col-sm-3 col-6" style="margin-bottom: 10px">
                @if (empty($country))
                @include('web-views.partials._single-product',['product'=>$product])
                @else
                @if($product['country'] == $country)
                {{-- {{ dd($product['country']) }} --}}
                @include('web-views.partials._single-product',['product'=>$product])
                @else
                <div id="empty" class="empty"></div>
                @endif
                @endif
        </div>
    @endif
    @endforeach
    </div>
  </section>
  @endif
  @endforeach


  <!-- Product widgets-->
  <section class="container pb-4 pb-md-5 rtl">
    <div class="row">
      <!-- Bestsellers-->
      <div class="col-12 col-sm-6 col-md-4 mb-2 py-3">
        <div class="widget">
          <div class="d-flex justify-content-between">
            <h3 class="widget-title">{{ \App\CPU\translate('best_sellings')}}</h3>
            <div>
              <a class="btn btn-outline-accent btn-sm"
                href="{{route('products',['data_from'=>'best-selling','page'=>1])}}">{{ \App\CPU\translate('view_all')}}
                <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'
                  }}"></i>
              </a>
            </div>
          </div>
          @foreach($bestSellProduct as $key=>$bestSell)
          @if($bestSell->product && $key<4) <div class="media align-items-center pt-2 pb-2 mb-1"
            data-href="{{route('product',$bestSell->product->slug)}}">
            <a class="d-block {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
              href="{{route('product',$bestSell->product->slug)}}">
              <img style="height: 54px; width: 54px"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$bestSell->product['thumbnail']}}"
                alt="Product" />
            </a>
            <div class="media-body">
              <h6 class="widget-product-title">
                <a class="ptr" href="{{route('product',$product->slug)}}">{{substr($bestSell->product['name'],0,30)}}
                  {{strlen($bestSell->product['name'])>30?'...':''}}</a>
              </h6>
              <div class="widget-product-meta">
                <span class="text-accent">
                  {{\App\CPU\Helpers::currency_converter(
                  $bestSell->product->unit_price-(\App\CPU\Helpers::get_product_discount($bestSell->product,$bestSell->product->unit_price))
                  )}}

                  @if($bestSell->product->discount > 0)
                  <strike style="font-size: 12px!important;color: grey!important;">
                    {{\App\CPU\Helpers::currency_converter($bestSell->product->unit_price)}}
                  </strike>
                  @endif
                </span>
              </div>
            </div>
        </div>
        @endif
        @endforeach
      </div>
    </div>
    <!-- New arrivals-->
    <div class="col-12 col-sm-6 col-md-4 mb-2 py-3">
      <div class="widget">
        <div class="d-flex justify-content-between">
          <h3 class="widget-title">{{\App\CPU\translate('new_arrivals')}}</h3>
          <div>
            <a class="btn btn-outline-accent btn-sm" href="{{route('products',['data_from'=>'latest','page'=>1])}}">{{
              \App\CPU\translate('view_all')}}
              <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'
                }}"></i>
            </a>
          </div>
        </div>
        @foreach($latest_products as $key=>$product)
        @if($key<4) <div class="media align-items-center pt-2 pb-2 mb-1"
          data-href="{{route('product',$product->slug)}}">
          <a class="d-block {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
            href="{{route('product',$product->slug)}}">
            <img style="height: 54px; width: 54px"
              onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
              src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
              alt="Product" />
          </a>
          <div class="media-body">
            <h6 class="widget-product-title">
              <a class="ptr" href="{{route('product',$product->slug)}}">
                {{substr($product['name'],0,30)}} {{strlen($product['name'])>30?'...':''}}
              </a>
            </h6>
            <div class="widget-product-meta">
              <span class="text-accent">
                {{\App\CPU\Helpers::currency_converter(
                $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                )}}
                @if($product->discount > 0)
                <strike style="font-size: 12px!important;color: grey!important;">
                  {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                </strike>
                @endif
              </span>
            </div>
          </div>
      </div>
      @endif
      @endforeach
    </div>
    </div>
    <!-- Top rated-->
    <div class="col-12 col-sm-6 col-md-4 mb-2 py-3">
      <div class="widget">
        <div class="d-flex justify-content-between">
          <h3 class="widget-title">{{\App\CPU\translate('top_rated')}}</h3>
          <div><a class="btn btn-outline-accent btn-sm"
              href="{{route('products',['data_from'=>'top-rated','page'=>1])}}">{{ \App\CPU\translate('view_all')}}
              <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'
                }}"></i></a></div>
        </div>
        @foreach($topRated as $key=>$top)
        @if($top->product && $key<4) <div class="media align-items-center pt-2 pb-2 mb-1"
          data-href="{{route('product',$top->product->slug)}}">
          <a class="d-block {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
            href="{{route('product',$top->product->slug)}}">
            <img style="height: 54px; width: 54px"
              onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
              src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$top->product['thumbnail']}}"
              alt="Product" />
          </a>
          <div class="media-body">
            <h6 class="widget-product-title">
              <a class="ptr" href="{{route('product',$top->product->slug)}}">
                {{substr($top->product['name'],0,30)}} {{strlen($top->product['name'])>30?'...':''}}
              </a>
            </h6>
            <div class="widget-product-meta">
              <span class="text-accent">
                {{\App\CPU\Helpers::currency_converter(
                $top->product->unit_price-(\App\CPU\Helpers::get_product_discount($top->product,$top->product->unit_price))
                )}}

                @if($top->product->discount > 0)
                <strike style="font-size: 12px!important;color: grey!important;">
                  {{\App\CPU\Helpers::currency_converter($top->product->unit_price)}}
                </strike>
                @endif
              </span>
            </div>
          </div>
      </div>
      @endif
      @endforeach
    </div>
    </div>
    </div>
  </section>
  @endsection

  @push('script')
  {{-- Owl Carousel --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $('#flash-deal-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 4
                },
                //Extra extra large
                1400: {
                    items: 4
                }
            }
        })

        $('#web-feature-deal-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 3
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 3
                },
                //Extra large
                1200: {
                    items: 3
                },
                //Extra extra large
                1400: {
                    items: 3
                }
            }
        })

        $( window ).on( "load",function() {
            var work = $(".empty").parent('div').remove();
            console.log( work );
        });
  </script>

  <script>
    $('#brands-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 3
                },
                375: {
                    items: 3
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 7
                },
                //Large
                992: {
                    items: 9
                },
                //Extra large
                1200: {
                    items: 11
                },
                //Extra extra large
                1400: {
                    items: 12
                }
            }
        })
  </script>

  <script>
    $('#category-slider, #top-seller-slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 5,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 3
                },
                375: {
                    items: 3
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 6
                },
                //Large
                992: {
                    items: 8
                },
                //Extra large
                1200: {
                    items: 10
                },
                //Extra extra large
                1400: {
                    items: 11
                }
            }
        })
  </script>
  @endpush

