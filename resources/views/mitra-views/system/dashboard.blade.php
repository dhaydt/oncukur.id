@extends('layouts.back-end.app-mitra')

@section('title', \App\CPU\translate('Mitra\'s_Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .grid-card {
            border: 2px solid #00000012;
            border-radius: 10px;
            padding: 10px;
        }

        .ewallet{
            font-size: 16px;
            font-weight: 600
        }

        .saldo{
            font-size: 20px;
        }

        .label_1 {
            /*position: absolute;*/
            font-size: 10px;
            background: #FF4C29;
            color: #ffffff;
            width: 80px;
            padding: 2px;
            font-weight: bold;
            border-radius: 6px;
            text-align: center;
        }

        .center-div {
            text-align: center;
            border-radius: 6px;
            padding: 6px;
            border: 2px solid #8080805e;
        }
    </style>
@endpush

@section('content')
{{-- @php($data = ['pending' => 1,
    'confirmed' => 1,
    'processing' => 1,
    'out_for_delivery' => 1,
    'delivered' => 2,
    'canceled' => 3,
    'returned' => 2,
    'failed' => 1,

    'commission_given' => 0,
    'pending_withdraw' => 0,
    'delivery_charge_earned' => 0,
    'collected_cash' => 0,
    'total_tax_collected' => 0,
    'total_earning' => 0,
    'withdrawn' => 0,
    ]) --}}
    <div class="content container-fluid">
        <div class="page-header pb-0" style="border-bottom: 0!important">
            <div class="row">
                <div class="col-md-9">
                    <div class="flex-between row align-items-center mx-1">
                        <h1 class="page-header-title">
                            Dashboard
                        </h1>
                        <p>Welcome to mitra's Dashboard.</p>
                    </div>
                </div>
                <div class="col-md-3 d-flex justify-content-between align-items-center">
                    <div class="flex-between row align-items-center mx-1">
                        <div class="wallet d-flex justify-content-between align-items-center">
                            <label for="" class="ewallet mb-0">Saldo :</label>
                            <span class="badge badge-success saldo">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($mitra->wallet->total_earning))}}</span>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTopUp"><i class="fas fa-plus"></i> Saldo</button>
                        </div>
                        <div class="status d-flex justify-content-between align-items-center mt-2">
                            <label for="" class="mb-0 ewallet">Is Online :</label>
                            <div class="saklar d-flex align-items-center">
                                <label class="switch mb-0">
                                    <input type="checkbox"
                                        onclick="onlineChange('{{$mitra->is_online}}')" {{$mitra->is_online == 1?'checked':''}}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTopUp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Top Up Saldo</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                    <div class="modal-body">
                        <label for="inputPassword5">Pilih Nominal Top Up</label>
                        <select class="custom-select" name="nominal">
                            <option value="" selected>Pilih nominal</option>
                            <option value="50000">50.000</option>
                            <option value="100000">100.000</option>
                            <option value="200000">200.000</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="topUp()" class="btn btn-primary">Proses Top Up</button>
                    </div>
              </div>
            </div>
          </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="flex-between gx-2 gx-lg-3 mb-2">
                    <div style="{{Session::get('direction') === "rtl" ? 'margin-right:2px' : ''}};">
                        <h4><i style="font-size: 30px"
                            class="tio-chart-bar-4"></i>{{\App\CPU\translate('dashboard_order_statistics')}}</h4>
                    </div>
                    <div style="width: 20vw">
                        <select class="custom-select" name="statistics_type" onchange="order_stats_update(this.value)">
                            <option
                                value="overall" {{session()->has('statistics_type') && session('statistics_type') == 'overall'?'selected':''}}>
                                {{\App\CPU\translate('Overall Statistics')}}
                            </option>
                            <option
                                value="today" {{session()->has('statistics_type') && session('statistics_type') == 'today'?'selected':''}}>
                                {{\App\CPU\translate('Todays Statistics')}}
                            </option>
                            <option
                                value="this_month" {{session()->has('statistics_type') && session('statistics_type') == 'this_month'?'selected':''}}>
                                {{\App\CPU\translate('This Months Statistics')}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row gx-2 gx-lg-3" id="order_stats">
                    @include('mitra-views.partials._dashboard-order-stats',['data'=>$data])
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="flex-between gx-2 gx-lg-3 mb-2">
                    <div>
                        <h4><i style="font-size: 30px"
                            class="tio-wallet"></i>{{\App\CPU\translate('mitra_wallet')}}</h4>
                    </div>
                </div>
                <div class="row gx-2 gx-lg-3" id="order_stats">
                    @include('mitra-views.partials._dashboard-wallet-stats',['data'=>$data])
                </div>

                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-6 for-card col-md-6 mt-4">
                        <div class="card for-card-body-2 shadow h-100  badge-primary"
                            style="background: #362222!important;">
                            <div class="card-body text-light">
                                <div class="flex-between no-gutters align-items-center">
                                    <div>
                                        <div class="font-weight-bold text-uppercase for-card-text mb-1">
                                            {{\App\CPU\translate('Withdrawable_balance')}}
                                        </div>
                                        <div
                                            class="for-card-count">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['total_earning']))}}</div>
                                    </div>
                                    <div>
                                        <a href="javascript" style="background: #3A6351!important;"
                                            class="btn btn-primary"
                                            data-toggle="modal" data-target="#balance-modal">
                                            <i class="tio-wallet-outlined"></i> {{\App\CPU\translate('Withdraw')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="balance-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{\App\CPU\translate('Withdraw Request')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{route('mitra.withdraw.request')}}" method="post">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">{{\App\CPU\translate('Amount')}}:</label>
                                            <input type="number" name="amount" step=".01"
                                                value="{{\App\CPU\BackEndHelper::usd_to_currency($data['total_earning'])}}"
                                                class="form-control" id="">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                                        @if(auth('mitra')->user()->account_no==null || auth('mitra')->user()->bank_name==null)
                                            <button type="button" class="btn btn-primary" onclick="call_duty()">
                                                {{\App\CPU\translate('Incomplete bank info')}}
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Request')}}</button>
                                        @endif
                                    </div>
                                </form>
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
                                            class="for-card-count">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['withdrawn']))}}</div>
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
@endsection

@push('script')
    <script src="{{asset('assets/back-end')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('assets/back-end')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{asset('assets/back-end')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush

@push('script_2')
    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function () {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
    </script>

    <script>
        function topUp(){
            var val = $('select[name="nominal"]').val();
            if(val == ""){
                toastr.warning('Pilih nominal top up anda!')
            }
            console.log('topup', val);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: `{{ route('mitra.mitra.topup') }}`,
                method: 'POST',
                data: {
                    nominal: val
                },
                success: function(data){
                    console.log('resp', data.payment_url);
                    location.href = data.payment_url;
                }
            })
        }

        function call_duty() {
            toastr.warning('{{\App\CPU\translate('Update your bank info first!')}}', '{{\App\CPU\translate('Warning')}}!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>

    <script>
        function onlineChange(val){
            var value = val;
            if(value == 0){
                value = 1;
            }else{
                value = 0;
            }
            console.log('online', value)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('mitra.mitra.online')}}',
                data: {
                    online: value
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    console.log('data', data)
                    if(data.status == 200){
                        toastr.success(data.message);
                    }else{
                        toastr.warning(data.message);
                    }
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('seller.dashboard.order-stats')}}',
                data: {
                    statistics_type: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    $('#order_stats').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }

        function business_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.dashboard.business-overview')}}',
                data: {
                    business_overview: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    console.log(data.view)
                    $('#business-overview-board').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>
@endpush
