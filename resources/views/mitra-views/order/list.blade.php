@extends('layouts.back-end.app-mitra')
@section('title', \App\CPU\translate('Order List'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-sm">
                <h1 class="page-header-title">{{\App\CPU\translate('Orders')}} <span
                        class="badge badge-soft-dark ml-2">{{$orders->total()}}</span>
                </h1>
            </div>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{\App\CPU\translate('order_table')}} </h5>
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6 mb-3 mb-lg-0">
                                <form action="{{ url()->current() }}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('search')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                   class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                   style="width: 100%">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CPU\translate('SL#')}}</th>
                                    <th>{{\App\CPU\translate('Order')}}</th>
                                    <th>{{\App\CPU\translate('Type')}}</th>
                                    <th>{{\App\CPU\translate('customer_name')}}</th>
                                    <th>{{\App\CPU\translate('Phone')}}</th>
                                    <th>{{\App\CPU\translate('Payment_status')}}</th>
                                    <th>{{\App\CPU\translate('Total')}}</th>
                                    <th>{{\App\CPU\translate('Status')}} </th>
                                    <th>{{\App\CPU\translate('Mitra')}} </th>
                                    <th style="width: 30px">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $k=>$order)
                                    <tr>
                                        <td>
                                            {{$orders->firstItem()+$k}}
                                        </td>
                                        <td>
                                            <a href="{{route('mitra.orders.details',$order['id'])}}">{{$order['id']}}</a>
                                        </td>
                                        <td>
                                            @if($order->order_type == 'order')
                                            <span class="badge badge-soft-success text-success text-capitalize">
                                                    {{$order->order_type}}
                                            </span>
                                            @else
                                                <span class="badge badge-soft-primary text-primary text-capitalize">{{$order->order_type}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-capitalize"> {{$order->customer ? $order->customer['f_name'].' '.$order->customer['l_name'] : 'Customer Data not found'}}</td>
                                        <td>{{ $order->customer ? $order->customer->phone : '' }}</td>
                                        <td class="text-center">
                                            @if($order->payment_status=='paid')
                                                <span class="badge badge-soft-success">
                                                <span class="legend-indicator bg-success" style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('paid')}}
                                                </span>
                                            @else
                                                <span class="badge badge-soft-danger">
                                                <span class="legend-indicator bg-danger" style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('unpaid')}}
                                                </span>
                                            @endif
                                        </td>
                                        <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}</td>
                                            <td class="text-capitalize ">
                                                @if($order->order_status=='pending')
                                                    <label
                                                        class="badge badge-soft-warning">{{\App\CPU\translate('need_confirmation')}}
                                                    </label>
                                                @elseif($order->order_status=='processing' || $order->order_status=='out_for_delivery')
                                                    <label
                                                        class="badge badge-warning">{{\App\CPU\translate($order['order_status'])}}</label>
                                                @elseif($order->order_status=='delivered' || $order->order_status=='confirmed')
                                                    <label
                                                        class="badge badge-success">{{\App\CPU\translate($order['order_status'])}}</label>
                                                @elseif($order->order_status=='returned')
                                                    <label
                                                        class="badge badge-danger">{{\App\CPU\translate($order['order_status'])}}</label>
                                                @else
                                                    <label
                                                        class="badge badge-danger">{{\App\CPU\translate($order['order_status'])}}</label>
                                                @endif
                                            </td>
                                            <td class="text-center text-capitalize">
                                                @if ($order['order_type'] == 'order')
                                                    {{ \app\CPU\helpers::mitra_name($order['mitra_id']) }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary dropdown-toggle"
                                                            type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i class="tio-settings"></i>
                                                    </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item"
                                                            href="{{route('seller.orders.details',[$order['id']])}}"><i
                                                                class="tio-visible"></i> {{\App\CPU\translate('view')}}</a>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{route('seller.orders.generate-invoice',[$order['id']])}}"><i
                                                                class="tio-download"></i> {{\App\CPU\translate('invoice')}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Footer -->
                     <div class="card-footer">
                        {{$orders->links()}}
                    </div>
                    @if(count($orders)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
