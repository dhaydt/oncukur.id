@extends('layouts.back-end.app')
{{-- {{ dd($seller) }} --}}
@section('title', $seller->name ? $seller->name : \App\CPU\translate("Mitra_name"))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard.index')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('mitra_Details')}}</li>
            </ol>
        </nav>

        <!-- Page Heading -->
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                <a href="{{route('admin.mitras.mitra-list')}}" class="btn btn-primary mt-3 mb-3">{{\App\CPU\translate('Back_to_mitra_list')}}</a>
            </div>
            <div>
                @if ($seller->status=="pending")
                    <div class="mt-4 pr-2 float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                        <div class="flex-start">
                            <h4 class="mx-1"><i class="tio-shop-outlined"></i></h4>
                            <div><h4>{{\App\CPU\translate('user_request_for_become_a_mitra.')}}</h4></div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.mitras.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="review">
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Review_Document')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.mitras.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger">{{\App\CPU\translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
                @if ($seller->status=="review")
                    <div class="mt-4 pr-2 float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                        <div class="flex-start">
                            <h4 class="mx-1"><i class="tio-shop-outlined"></i></h4>
                            <div><h4>{{\App\CPU\translate('user_request_for_become_a_mitra.')}}</h4></div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.mitras.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.mitras.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger">{{\App\CPU\translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Page Header -->

    <div class="page-header">
        <div class="flex-between row mx-1">
            <div>
                <h1 class="page-header-title">{{ $seller->name }} <span class="text-uppercase">( {{ $seller->shop? $seller->shop->name : "Outlet Name : Ask outlet administrator for update" }} )</span></h1>
            </div>
        </div>
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.mitras.view',$seller->id) }}">{{\App\CPU\translate('Outlet')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                        href="{{ route('admin.mitras.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{\App\CPU\translate('Order / Booking')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                        href="{{ route('admin.mitras.view',['id'=>$seller->id, 'tab'=>'setting']) }}">{{\App\CPU\translate('Setting')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                        href="{{ route('admin.mitras.view',['id'=>$seller->id, 'tab'=>'transaction']) }}">{{\App\CPU\translate('Transaction')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.mitras.view',['id'=>$seller->id, 'tab'=>'review']) }}">{{\App\CPU\translate('Review')}}</a>
                    </li>

            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->
        <div class="card mb-3">
            <div class="card-body">
                <div class=" gx-2 gx-lg-3 mb-2">
                    <div>
                        <h4><i style="font-size: 30px"
                            class="tio-wallet"></i>{{\App\CPU\translate('mitra_wallet')}}</h4>
                    </div>
                    <div class="row gx-2 gx-lg-3" id="order_stats">
                        <div class="flex-between" style="width: 100%">
                            <div class="mb-3 mb-lg-0" style="width: 18%">
                                <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #22577A;">
                                    <h1 class="p-2 text-white">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->commission_given)) : 0}}</h1>
                                    <div class="text-uppercase">{{\App\CPU\translate('commission_given')}}</div>
                                </div>
                            </div>

                            <div class="mb-3 mb-lg-0" style="width: 18%">
                                <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #595260;">
                                    <h1 class="p-2 text-white">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->pending_withdraw)) : 0}}</h1>
                                    <div class="text-uppercase">{{\App\CPU\translate('pending_withdraw')}}</div>
                                </div>
                            </div>

                            <div class="mb-3 mb-lg-0" style="width: 18%">
                                <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #a66f2e;">
                                    <h1 class="p-2 text-white">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->delivery_charge_earned)) : 0}}</h1>
                                    <div class="text-uppercase">{{\App\CPU\translate('delivery_charge_earned')}}</div>
                                </div>
                            </div>

                            <div class="mb-3 mb-lg-0" style="width: 18%">
                                <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #6E85B2;">
                                    <h1 class="p-2 text-white">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->collected_cash)) : 0}}</h1>
                                    <div class="text-uppercase">{{\App\CPU\translate('collected_cash')}}</div>
                                </div>
                            </div>

                            <div class="mb-3 mb-lg-0" style="width: 18%">
                                <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #6D9886;">
                                    <h1 class="p-2 text-white">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->total_tax_collected)) : 0}}</h1>
                                    <div class="text-uppercase">{{\App\CPU\translate('total_collected_tax')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-6 for-card col-md-6 mt-4">
                        <div class="card for-card-body-2 shadow h-100  badge-primary"
                             style="background: #362222!important;">
                            <div class="card-body text-light">
                                <div class="flex-between row no-gutters align-items-center">
                                    <div>
                                        <div class="font-weight-bold text-uppercase for-card-text mb-1">
                                            {{\App\CPU\translate('Withdrawable_balance')}}
                                        </div>
                                        <div
                                            class="for-card-count">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->total_earning)) : 0 }}</div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-6 for-card col-md-6 mt-4" style="cursor: pointer">
                        <div class="card  shadow h-100 for-card-body-3 badge-info"
                             style="background: #171010!important;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class=" font-weight-bold for-card-text text-uppercase mb-1">{{\App\CPU\translate('withdrawn')}}</div>
                                        <div
                                            class="for-card-count">{{$seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->withdrawn)) : 0}}</div>
                                    </div>
                                    <div class="col-auto for-margin">
                                        <i class="tio-money-vs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-capitalize">
                        {{\App\CPU\translate('Mitra')}} {{\App\CPU\translate('Account')}} <br>
                        @if($seller->status=='approved')
                            <form class="d-inline-block" action="{{route('admin.mitras.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="suspended">
                                <button type="submit"
                                        class="btn btn-outline-danger">{{\App\CPU\translate('suspend')}}</button>
                            </form>
                        @elseif($seller->status=='rejected' || $seller->status=='suspended')
                            <form class="d-inline-block" action="{{route('admin.mitras.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                        class="btn btn-outline-success">{{\App\CPU\translate('activate')}}</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="flex-start row">
                                <div class="col-3"><h4>Status : </h4></div>
                                <div class="col-9"><h4>{!! $seller->status=='approved'?'<label class="badge badge-success">Active</label>':'<label class="badge badge-danger">In-Active</label>' !!}</h4></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-3"><h5>{{\App\CPU\translate('name')}} : </h5></div>
                                <div class="col-9"><h5>{{$seller->name}}</h5></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-3"><h5>{{\App\CPU\translate('Email')}} : </h5></div>
                                <div class="col-9"><h5>{{$seller->email}}</h5></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-3"><h5>{{\App\CPU\translate('Phone')}} : </h5></div>
                                <div class="col-9"><h5>{{$seller->phone}}</h5></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-3"><h5>{{\App\CPU\translate('ID_TikTok')}} : </h5></div>
                                <div class="col-9"><h5>{{$seller->id_tiktok}}</h5></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-3"><h5>{{\App\CPU\translate('TikTok_Name')}} : </h5></div>
                                <div class="col-9"><h5>{{$seller->username_tiktok}}</h5></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-3"><h5>{{\App\CPU\translate('KTP')}} : </h5></div>
                                <div class="col-9">
                                    <div class="card">
                                        <img src="{{ asset('storage/ktp'.'/'.$seller->ktp) }}" alt="" class="w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($seller->shop)
                <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{\App\CPU\translate('Outlet')}} {{\App\CPU\translate('info')}}
                </div>
                <div class="card-body" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                    }};">
                    <div class="flex-start row">
                        <div class="col-3">
                            <h5>{{\App\CPU\translate('Outlet_name')}}</h5>
                        </div>
                        <div class="col-9">
                            <h5 class="text-capitalize">: {{$seller->shop->name}}</h5>
                        </div>
                    </div>
                    <div class="flex-start row">
                        <div class="col-3">
                            <h5>{{\App\CPU\translate('Phone')}}</h5>
                        </div>
                        <div class="col-9">
                            <h5>: {{$seller->shop->contact}}</h5>
                        </div>
                    </div>
                    <div class="flex-start row">
                        <div class="col-3">
                            <h5>{{\App\CPU\translate('address')}}</h5>
                        </div>
                        <div class="col-9">
                            <h5>: {{$seller->shop->address}}</h5>
                        </div>
                    </div>
                    <div class="flex-start row">
                        <div class="col-3">
                            <h5>{{\App\CPU\translate('Chair')}}</h5>
                        </div>
                        <div class="col-9">
                            <h5 class="text-capitalize">: {{$seller->shop->chair}}</h5>
                        </div>
                    </div>
                    <div class="flex-start row">
                        <div class="col-3">
                            <h5>{{\App\CPU\translate('Mitra_capacity')}}</h5>
                        </div>
                        <div class="col-9">
                            <h5 class="text-capitalize">: {{$seller->shop->capacity}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @endif

            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        {{\App\CPU\translate('bank_info')}}
                    </div>
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="col-md-8 mt-2">
                            <div class="flex-start row">
                                <div class="col-5"><h4>{{\App\CPU\translate('bank_name')}} : </h4></div>
                                <div class="col-7"><h4>{{$seller->bank_name ? $seller->bank_name : 'No Data found'}}</h4></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-5"><h6>{{\App\CPU\translate('Branch')}} : </h6></div>
                                <div class="col-7"><h6>{{$seller->branch ? $seller->branch : 'No Data found'}}</h6></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-5"><h6>{{\App\CPU\translate('holder_name')}} : </h6></div>
                                <div class="col-7"><h6>{{$seller->holder_name ? $seller->holder_name : 'No Data found'}}</h6></div>
                            </div>
                            <div class="flex-start row">
                                <div class="col-5"><h6>{{\App\CPU\translate('account_no')}} : </h6></div>
                                <div class="col-7"><h6>{{$seller->account_no ? $seller->account_no : 'No Data found'}}</h6></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-6 mt-3">
                <form action="{{route('admin.sellers.sales-commission-update',[$seller['id']])}}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <label> Sales Commission : </label>
                            <label class="switch ml-3">
                                <input type="checkbox" name="status"
                                       class="status"
                                       value="1" {{$seller['sales_commission_percentage']!=null?'checked':''}}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <small class="badge badge-soft-danger mb-3">
                                If sales commission is disabled here, the system default commission will be applied.
                            </small>
                            <div class="form-group">
                                <label>Commission ( % )</label>
                                <input type="number" value="{{$seller['sales_commission_percentage']}}"
                                       class="form-control" name="commission">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div> --}}
        </div>
</div>
@endsection

@push('script')

@endpush
