<div class="flex-between" style="width: 100%">
    {{-- {{ dd($commission_data) }} --}}
    <div class="mb-3 mb-lg-0" style="width: 30%">
        <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #22577A;">
            <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['commission_given']))}}</h1>
            <div class="text-uppercase">{{\App\CPU\translate('commission_given_admin')}}</div>
        </div>
    </div>
    <div class="mb-3 mb-lg-0" style="width: 30%">
        <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #22577A;">
            <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['outlet_commission_given']))}}</h1>
            <div class="text-uppercase">{{\App\CPU\translate('outlet_commission_given_')}}</div>
        </div>
    </div>

    <div class="mb-3 mb-lg-0" style="width: 30%">
        <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #595260;">
            <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['pending_withdraw']))}}</h1>
            <div class="text-uppercase">{{\App\CPU\translate('pending_withdraw')}}</div>
        </div>
    </div>

    {{-- <div class="mb-3 mb-lg-0" style="width: 18%">
        <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #6E85B2;">
            <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['collected_cash']))}}</h1>
            <div class="text-uppercase">{{\App\CPU\translate('collected_cash')}}</div>
        </div>
    </div> --}}

    {{-- <div class="mb-3 mb-lg-0" style="width: 18%">
        <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #6D9886;">
            <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['total_tax_collected']))}}</h1>
            <div class="text-uppercase">{{\App\CPU\translate('total_collected_tax')}}</div>
        </div>
    </div> --}}
</div>
