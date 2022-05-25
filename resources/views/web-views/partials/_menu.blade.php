{{-- @foreach($categories as $category)
<div class="category_div" style="height: 132px; width: 100%;">
    <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
        <img style="vertical-align: middle; padding: 16%;height: 98px"
            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{asset("
            storage/category/$category->icon")}}"
        alt="{{$category->name}}">
        <p class="text-center small" style="margin-top: -20px">{{Str::limit($category->name, 17)}}</p>
    </a>
</div>
@endforeach --}}
<div class="owl-carousel owl-theme " id="category-slider">
    <div class="category_div">
        <a href="javascript:" class="route-menu">
            <img style="vertical-align: middle; padding: 16%;height: 98px"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{ asset('assets/front-end/img/wahl.png') }}" alt="Wahl">
            <p class="text-center small" style="margin-top: -20px">OnCukur</p>
        </a>
    </div>
    <div class="category_div">
        <a href="{{ route('onlocation') }}" class="route-menu">
            <img style="vertical-align: middle; padding: 16%;height: 98px"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{ asset('assets/front-end/img/map.png') }}" alt="Map">
            <p class="text-center small" style="margin-top: -20px">OnLokasi</p>
        </a>
    </div>
    <div class="category_div">
        <a href="javascript:" class="route-menu">
            <img style="vertical-align: middle; padding: 16%;height: 98px"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{ asset('assets/front-end/img/hair.png') }}" alt="Style">
            <p class="text-center small" style="margin-top: -20px">Style Kamu</p>
        </a>
    </div>
    <div class="category_div">
        <a href="javascript:" class="route-menu">
            <img style="vertical-align: middle; padding: 16%;height: 98px"
                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{ asset('assets/front-end/img/other.png') }}" alt="other">
            <p class="text-center small" style="margin-top: -20px">Lain - Lain</p>
        </a>
    </div>
</div>
