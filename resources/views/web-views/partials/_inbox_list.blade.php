@if (isset($unique_shops))
    @foreach($unique_shops as $key=>$shop)
        <div class="chat_list @if ($key == 0) btn-primary @endif"
            id="user_{{$shop->shop_id}}">
            <div class="chat_people" id="chat_people">
                <div class="chat_img">
                    @php($mitra = \App\CPU\Helpers::getMitra($shop['mitra_id']))
                    @if ($shop['mitra_id'] == 0)
                        <img
                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                            src="{{asset('storage/outlet/'.$shop->image)}}"
                            style="border-radius: 10px">

                    @elseif($shop['mitra_id'] != 0)
                        <img
                            src="{{asset('storage/mitra/'.$mitra->image)}}"
                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                            style="border-radius: 10px">
                    @endif
                </div>
                {{-- {{ dd($shop) }} --}}
                <div class="chat_ib">
                    @if ($shop["mitra_id"] == 0 )
                        <input type="hidden" id="receiver-{{ $shop->shop_id }}" value="Outlet">
                        <input type="hidden" id="mitra_ids-{{ $shop->shop_id }}" value="{{ $shop['seller_id'] }}">
                    @else
                        <input type="hidden" id="receiver-{{ $shop->shop_id }}" value="Mitra">
                        <input type="hidden" id="mitra_ids-{{ $shop->shop_id }}" value="{{ $shop['mitra_id'] }}">
                    @endif
                    <h5 class="seller text-capitalize @if($shop->seen_by_customer)active-text @endif"
                        id="{{$shop->shop_id}}">@if ($shop['mitra_id'] == 0) Outlet @else
                            {{ $mitra->name }}
                        @endif {{' ('.$shop->name.')'}}</h5>
                </div>
            </div>
        </div>
    @endForeach
@endif
