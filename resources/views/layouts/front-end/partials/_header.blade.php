{{-- navabr / _header --}}
<style>
    #nav-global-location-slot {
        border: 2px solid transparent;
        padding: 10px;
        transition: .3s;
        cursor: pointer;
        border-radius: 4px;
    }
    #nav-global-location-slot:hover {
        border: 2px solid #0f0f0f;
    }
    .nav-line-1.nav-progressive-content {
        font-size: 14px;
        line-height: 14px;
        transition: .3s;
        height: 14px;
        color: #9d9d9d;
        font-weight: 700;
    }

    .nav-line-2.nav-progressive-content {
        font-size: 16px;
        font-weight: 700;
        transition: .3s;
    }

  .card-body.search-result-box {
    overflow: scroll;
    height: 400px;
    overflow-x: hidden;
  }

  .active .seller {
    font-weight: 700;
  }

  ul.navbar-nav.mega-nav .nav-item .nav-link {
    color: #000 !important;
  }

  .for-count-value {
    position: absolute;

    right: 0.6875rem;
    ;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;

    color: {
        {
        $web_config['primary_color']
      }
    }

    ;

    font-size: .75rem;
    font-weight: 500;
    text-align: center;
    line-height: 1.25rem;
  }

  .count-value {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;

    color: {
        {
        $web_config['primary_color']
      }
    }

    ;

    font-size: .75rem;
    font-weight: 500;
    text-align: center;
    line-height: 1.25rem;
  }

  @media (min-width: 992px) {
    .navbar-sticky.navbar-stuck .navbar-stuck-menu.show {
      display: block;
      height: 55px !important;
    }
  }

  @media (min-width: 768px) {
    .navbar-stuck-menu {
      background-color: {
          {
          $web_config['primary_color']
        }
      }

      ;
      line-height: 15px;
      padding-bottom: 6px;
    }

  }

  @media (max-width: 767px) {
    .search_button {
      background-color: transparent !important;
    }

    .search_button .input-group-text i {
      color: {
          {
          $web_config['primary_color']
        }
      }

       !important;
    }

    .navbar-expand-md .dropdown-menu>.dropdown>.dropdown-toggle {
      position: relative;

      padding- {
          {
          Session: :get('direction')==="rtl"? 'left': 'right'
        }
      }

      : 1.95rem;
    }

    .mega-nav1 {
      background: white;

      color: {
          {
          $web_config['primary_color']
        }
      }

       !important;
      border-radius: 3px;
    }

    .mega-nav1 .nav-link {
      color: {{ $web_config['primary_color']}} !important; }}

  @media (max-width: 768px) {
    .tab-logo {
      width: 10rem;
    }
  }

  @media (max-width: 360px) {
    .mobile-head {
      padding: 3px;
    }
  }

  @media (max-width: 471px) {
    .navbar-brand img {}

    .mega-nav1 {
      background: white;
      color: #000 !important;
      border-radius: 3px;
    }

    .mega-nav1 .nav-link {
      color: #000 !important;
    }
  }
</style>

<header class="box-shadow-sm rtl">
    <div class="navbar-sticky bg-light mobile-head">
            <div class="navbar navbar-expand-md navbar-light">
            <div class="container ">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <a class="navbar-brand d-none d-sm-block {{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3' }}
                flex-shrink-0 tab-logo" href="{{route('home')}}" style="min-width: 7rem;">
                <img width="250" height="60" style="height: 60px!important;"
                                src="{{asset("storage/company")."/".$web_config['web_logo']->value}}"
                                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                alt="{{$web_config['name']->value}}"/>
                </a>

                <a class="navbar-brand d-sm-none mr-auto ml-2"
                href="{{route('home')}}">
                <img style="height: 40px!important; width:auto;" src="{{asset("storage/company")."/".$web_config['mob_logo']->value}}"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                alt="{{$web_config['name']->value}}"/>
                </a>

                <div class="input-group-overlay d-none d-md-block mx-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left' }}">
                    <form action="{{route('products')}}" type="submit" class="search_form">
                        <input class="form-control appended-form-control search-bar-input" type="text" autocomplete="off"
                        placeholder="{{\App\CPU\translate('search')}}" name="name"
                        style="border: 2px solid #23a4e5; border-radius: 50px; border-top-right-radius: 50px !important; border-bottom-right-radius: 50px !important;">

                        <button class="input-group-append-overlay search_button d-flex align-items-center" type="submit"
                            style="border-radius: {{Session::get('direction') === " rtl" ? '50px 0px 0px 50px; right: unset; left: 0'
                            : '0px 50px 50px 0px; left: unset; right: 0' }};">
                            <span class="input-group-text" style="font-size: 20px;">
                                <i class="czi-search text-white"></i>
                            </span>
                        </button>

                        <input name="data_from" value="search" hidden>
                        <input name="page" value="1" hidden>
                        <diV class="card search-card"
                        style="position: absolute;background: white;z-index: 999;width: 100%;display: none">
                            <div class="card-body search-result-box" style="overflow:scroll; height:400px;overflow-x: hidden"></div>
                        </diV>
                    </form>
                </div>

                <!-- Toolbar-->
                <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
                    <a class="navbar-tool navbar-stuck-toggler" href="#">
                        <span class="navbar-tool-tooltip">Expand menu</span>
                        <div class="navbar-tool-icon-box">
                            <i class="navbar-tool-icon czi-menu"></i>
                        </div>
                    </a>
                {{-- <div class="navbar-tool dropdown {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
                    <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('wishlists')}}">
                        <span class="navbar-tool-label">
                        <span class="countWishlist">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                        </span>
                        <i class="navbar-tool-icon czi-heart"></i>
                    </a>
                </div> --}}
                @if(auth('customer')->check())
                <div class="dropdown dropstart user-stat">
                    <a class="navbar-tool ml-2 mr-2 " type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="navbar-tool-icon-box bg-secondary">
                        <div class="navbar-tool-icon-box bg-secondary">
                        <img style="width: 40px;height: 40px"
                            src="{{asset('storage/profile/'.auth('customer')->user()->image)}}"
                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                            class="img-profile rounded-circle">
                        </div>
                    </div>
                    <div class="navbar-tool-text">
                        <small>Hello, {{auth('customer')->user()->f_name}}</small>
                        Dashboard
                    </div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('account-oder')}}"> {{ \App\CPU\translate('my_order')}} </a>
                    <a class="dropdown-item" href="{{route('user-account')}}"> {{ \App\CPU\translate('my_profile')}}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('customer.auth.logout')}}">{{ \App\CPU\translate('logout')}}</a>
                    </div>
                </div>
                @else
                <div class="dropdown dropstart user-stat">
                    <a class="navbar-tool ml-3" type="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="navbar-tool-icon-box bg-secondary">
                        <div class="navbar-tool-icon-box bg-secondary">
                        <i class="navbar-tool-icon czi-user"></i>
                        </div>
                    </div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                    style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
                    <a class="dropdown-item" href="{{route('customer.auth.login')}}">
                        <i class="fa fa-sign-in {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                        {{\App\CPU\translate('sing_in')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('customer.auth.register')}}">
                        <i class="fa fa-user-circle {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2'
                        }}"></i>{{\App\CPU\translate('sing_up')}}
                    </a>
                    </div>
                </div>
                @endif
                <div id="cart_items">
                    @include('layouts.front-end.partials.cart')
                </div>
                </div>
            </div>
            </div>
            <div class="navbar navbar-expand-md navbar-stuck-menu bg-light border-top">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarCollapse" style="text-align: {{Session::get('direction') === "
                    rtl" ? 'right' : 'left' }}">

                    <!-- Mega menu -->
                    @php($categories=\App\CPU\CategoryManager::parents())
                    <ul class="navbar-nav mega-nav pr-2 pl-2 {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }} d-none">
                                    <!--web-->
                            <li class=" nav-item {{!request()->is('/')?'dropdown':''}}">
                                <a class="nav-link dropdown-toggle {{Session::get('direction') === " rtl" ? 'pr-0' : 'pl-0' }}" href="#"
                                    data-bs-toggle="dropdown" style="{{request()->is('/')?'pointer-events: none':''}}">
                                    <i class="czi-menu align-middle mt-n1 {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                                    <span style="margin-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: 40px
                                        !important;margin-{{Session::get('direction')==="rtl" ? 'left' : 'right' }}: 50px">
                                        {{ \App\CPU\translate('categories')}}
                                    </span>
                                </a>
                                @if(request()->is('/'))
                                    <ul class="dropdown-menu"
                                        style="right: 0%; display: block!important;margin-top: 7px; box-shadow: none;min-width: 303px !important;{{Session::get('direction') === "rtl" ? 'margin-right: 1px!important;text-align: right;' : 'margin-left: 1px!important;text-align: left;'}}padding-bottom: 0px!important;">
                                        @foreach($categories as $key=>$category)
                                            @if($key<11)
                                                <li class="dropdown">
                                                    <a class="dropdown-item flex-between"
                                                        <?php if ($category->childes->count() > 0) echo "data-bs-toggle='dropdown'"?> href="javascript:"
                                                        onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                        <div>
                                                            <img
                                                                src="{{asset("storage/category/$category->icon")}}"
                                                                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                                style="width: 50px; height: 50px; ">
                                                            <span
                                                                class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$category['name']}}</span>
                                                        </div>
                                                        @if ($category->childes->count() > 0)
                                                            <div>
                                                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;" ></i>
                                                            </div>
                                                        @endif
                                                    </a>
                                                    @if($category->childes->count()>0)
                                                        <ul class="dropdown-menu"
                                                            style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                            @foreach($category['childes'] as $subCategory)
                                                                <li class="dropdown">
                                                                    <a class="dropdown-item flex-between"
                                                                        <?php if ($subCategory->childes->count() > 0) echo "data-bs-toggle='dropdown'"?> href="javascript:"
                                                                        onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                                        <div>
                                                                            <span
                                                                                class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$subCategory['name']}}</span>
                                                                        </div>
                                                                        @if ($subCategory->childes->count() > 0)
                                                                            <div>
                                                                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;"></i>
                                                                            </div>
                                                                        @endif
                                                                    </a>
                                                                    @if($subCategory->childes->count()>0)
                                                                        <ul class="dropdown-menu"
                                                                            style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                                            @foreach($subCategory['childes'] as $subSubCategory)
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subSubCategory['name']}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                        <a class="dropdown-item" href="{{route('categories')}}"
                                            style="{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 29%">
                                            {{\App\CPU\translate('view_more')}}
                                        </a>
                                    </ul>
                                @else
                                    <ul class="dropdown-menu"
                                        style="right: 0; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                        @foreach($categories as $category)
                                            <li class="dropdown">
                                                <a class="dropdown-item flex-between <?php if ($category->childes->count() > 0) echo "data-bs-toggle='dropdown"?> "
                                                    <?php if ($category->childes->count() > 0) echo "data-bs-toggle='dropdown'"?> href="javascript:"
                                                    onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                    <div>
                                                        <img src="{{asset("storage/category/$category->icon")}}"
                                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" style="width: 50px; height: 50px;">
                                                        <span class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$category['name']}}</span>
                                                    </div>
                                                    @if ($category->childes->count() > 0)
                                                        <div>
                                                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;"></i>
                                                        </div>
                                                    @endif
                                                </a>
                                                @if($category->childes->count()>0)
                                                    <ul class="dropdown-menu"
                                                        style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                        @foreach($category['childes'] as $subCategory)
                                                            <li class="dropdown">
                                                                <a class="dropdown-item flex-between <?php if ($subCategory->childes->count() > 0) echo "data-bs-toggle='dropdown"?> "
                                                                    <?php if ($subCategory->childes->count() > 0) echo "data-bs-toggle='dropdown'"?> href="javascript:"
                                                                    onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                                    <div>
                                                                        <span
                                                                            class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$subCategory['name']}}</span>
                                                                    </div>
                                                                    @if ($subCategory->childes->count() > 0)
                                                                        <div>
                                                                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;"></i>
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                                @if($subCategory->childes->count()>0)
                                                                    <ul class="dropdown-menu"
                                                                        style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                                        @foreach($subCategory['childes'] as $subSubCategory)
                                                                            <li>
                                                                                <a class="dropdown-item"
                                                                                    href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subSubCategory['name']}}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                        <a class="dropdown-item" href="{{route('categories')}}"
                                            style="{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 29%">
                                            {{\App\CPU\translate('view_more')}}
                                        </a>
                                    </ul>
                                @endif
                        </li>
                    </ul>

                    <!-- Mega menu end -->
                    <ul class="navbar-nav mega-nav1 pr-2 pl-2 d-none">
                        <!--mobile-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{Session::get('direction') === " rtl" ? 'pr-0' : 'pl-0' }}" href="#"
                                data-bs-toggle="dropdown">
                                <i class="czi-menu align-middle mt-n1 d-none {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                                <span style="margin-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: -8px !important;">{{ \App\CPU\translate('categories')}}</span>
                            </a>
                            <ul class="dropdown-menu" style="right: 0%; text-align: {{Session::get('direction') === " rtl" ? 'right'
                                : 'left' }};">
                                @foreach($categories as $category)
                                    <li class="dropdown">
                                        <a class="dropdown-item <?php if ($category->childes->count() > 0) echo " dropdown-toggle"?> "
                                            <?php if ($category->childes->count() > 0) echo "data-bs-toggle='dropdown'"?>
                                            href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                                            <img src="{{asset("storage/category/$category->icon")}}"
                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                            style="width: 50px; height: 40px; ">
                                            <span class="{{Session::get('direction') === " rtl" ? 'pr-3' : 'pl-3'
                                            }}">{{$category['name']}}</span>
                                        </a>
                                        @if($category->childes->count()>0)
                                            <ul class="dropdown-menu" style="right: 100%; text-align: {{Session::get('direction') === " rtl"
                                                ? 'right' : 'left' }};">
                                                @foreach($category['childes'] as $subCategory)
                                                <li class="dropdown">
                                                    <a class="dropdown-item <?php if ($subCategory->childes->count() > 0) echo " dropdown-toggle"?> "
                                                        <?php if ($subCategory->childes->count() > 0) echo "data-bs-toggle='dropdown'"?>
                                                        href="{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                        <span class="{{Session::get('direction') === " rtl" ? 'pr-3' : 'pl-3'
                                                        }}">{{$subCategory['name']}}</span>
                                                    </a>
                                                    @if($subCategory->childes->count()>0)
                                                        <ul class="dropdown-menu" style="right: 100%; text-align: {{Session::get('direction') === " rtl"
                                                            ? 'right' : 'left' }};">
                                                            @foreach($subCategory['childes'] as $subSubCategory)
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subSubCategory['name']}}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    <!-- Primary menu-->
                    <ul class="navbar-nav w-100" style="{{Session::get('direction') === " rtl" ? 'padding-right: 0px' : '' }}">
                        <li class="nav-item dropdown {{request()->is('/')?'active':''}}">
                            <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                                href="{{route('home')}}">{{ \App\CPU\translate('Home')}}</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                                href="{{url('explore')}}">
                                {{ \App\CPU\translate('Explore')}}
                            </a>
                        </li>

                        @php($seller_registration=\App\Model\BusinessSetting::where(['type'=>'seller_registration'])->first()->value)
                        @if($seller_registration)

                        <li class="nav-item dropdown">
                            <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                                href="{{route('mitra.auth.register')}}">
                                {{ \App\CPU\translate('Become a')}} {{ \App\CPU\translate('Mitra')}}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                                href="{{route('mitra.auth.login')}}">
                                {{ \App\CPU\translate('Mitra')}} {{ \App\CPU\translate('login')}}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark border-right py-2 mt-2" style="color: black !important"
                                ref="#" data-bs-toggle="dropdown">{{ \App\CPU\translate('Outlet') }} <i class="ms-2 fas fa-caret-down"></i></a>
                            <ul class="dropdown-menu scroll-bar" style="text-align: {{Session::get('direction') === " rtl" ? 'right'
                                : 'left' }};">
                                    <li class="pb-2 mb-2" style="border-bottom: 1px solid #e3e9ef; display:flex; justify-content:space-between;">
                                        <div>
                                            <a class="dropdown-item"
                                                href="{{route('seller.auth.login')}}">
                                                {{ \App\CPU\translate('Login')}} {{ \App\CPU\translate('Outlet')}}
                                            </a>
                                        </div>
                                    </li>
                                    <li style="display:flex; justify-content:space-between; ">
                                        <div>
                                            <a class="dropdown-item"
                                                href="{{route('seller.auth.register')}}">
                                                {{ \App\CPU\translate('Register')}} {{ \App\CPU\translate('Outlet')}}
                                            </a>
                                        </div>
                                    </li>
                            </ul>
                        </li>
                        @endif



                        {{-- @php( $local = \App\CPU\Helpers::default_lang())
                        <li class="nav-item dropdown ml-auto">
                            <a class="nav-link dropdown-toggle text-dark border-right py-2 mt-2" href="#" data-bs-toggle="dropdown"
                                style="color: black !important">
                                @foreach(json_decode($language['value'],true) as $data)
                                    @if($data['code']==$local)
                                        <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                        src="{{asset('assets/front-end')}}/img/flags/{{$data['code']}}.png" alt="Eng">
                                        {{$data['name']}}
                                    @endif
                                @endforeach
                            </a>

                            <ul class="dropdown-menu scroll-bar">
                                @foreach(json_decode($language['value'],true) as $key =>$data)
                                    @if($data['status']==1)
                                        <li>
                                            <a class="dropdown-item pb-1" href="{{route('lang',[$data['code']])}}">
                                                <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                                src="{{asset('assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                                alt="{{$data['name']}}" />
                                                <span
                                                style="text-transform: capitalize">{{\App\CPU\Helpers::get_language_name($data['code'])}}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>

                        @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))

                        @if($currency_model=='multi_currency')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-dark" style="color: black !important" href="#"
                                    data-bs-toggle="dropdown">
                                    <span>{{session('currency_code')}} {{session('currency_symbol')}}</span>
                                </a>
                                <ul class="dropdown-menu" style="min-width: 160px!important;">
                                    @foreach (\App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                        <li style="cursor: pointer" class="dropdown-item" onclick="currency_change('{{$currency['code']}}')">
                                        {{ $currency->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

@push('script')
<script>

fetch('https://ipapi.co/json/')
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    // console.log('location',data);

            $('#auto-loc').append(data.country_name)
            $('#nav-global-location-slot').attr('data-original-title', data.city + ', ' + data.region);
  });
  $(document).ready(function(){
    if($(window).width() > 500)
        {
            $('.user-stat').removeClass('dropstart')
        }else{
            $('.user-stat').addClass('dropstart')
        }
  })
</script>
@endpush
