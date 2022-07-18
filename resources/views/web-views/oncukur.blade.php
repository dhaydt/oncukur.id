@extends('layouts.front-end.app')
@section('title', 'OnCukur')

@push('css_or_js')
<style>
    #btnAction {
        background: #3878c7;
        padding: 10px 40px;
        border: #3672bb 1px solid;
        border-radius: 2px;
        color: #FFF;
        font-size: 0.9em;
        cursor: pointer;
        display: block;
        z-index: 200;
    }

    #btnAction:disabled {
        background: #6c99d2;
    }
    .cat-img{
        height: 100px;
    }

</style>
@endpush

@section('content')
<div class="container">
    <div class="col-12" style="width:85%;position: fixed;z-index: 9999;display: flex;align-items: center;justify-content: center;">
        <div id="getMitra" class="d-none">
            <img width="200"
            src="{{asset('storage/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
            onerror="this.src='{{asset('public/assets/front-end/img/loader.gif')}}'">
            sedang mencari mitra
        </div>
    </div>
    <div class="row">
        <input type="hidden" id="user" value="{{ auth('customer')->id() }}">
        <div class="col-12">
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <h3>Select Service to Order</h3>
                </div>
                <div class="card-body justify-content-center d-flex">
                    @foreach ($product as $p)
                    <div class="col-md-2">
                        <a href="javascript:" class="card shadow-sm nav-link" onclick="submit({{ $p->id }})">
                            <form action="" method="post" id="menu{{ $p->id }}">
                                @csrf
                                <input type="hidden" value="{{ $p->id }}" name="cat_id">
                                <input type="hidden" name="lat">
                                <input type="hidden" name="long">
                                <div class="card-header text-center">
                                    <h4>
                                        {{ $p->name }}
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <img class="cat-img" src="{{ asset('storage/category/').'/'.$p->icon }}" alt="">
                                </div>
                            </form>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="modalMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Oncukur Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="menuForm">
                    <div class="modal-body" id="menu">
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="booking()" class="btn btn-primary">Booking</button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection

@push('script')
    <script>
        $(document).ready(function(){
            if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                console.log('pos', pos)
                $('input[name=lat]').val(pos.lat);
                $('input[name=long]').val(pos.lng);

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

        function submit(id){
            var form = $('#menu' + id).serialize();

            $.ajax({
                url: `{{ route('menu.location') }}`,
                method: 'POST',
                data: form,
                beforeSend: function () {
                        $('#getMitra').removeClass('d-none');
                },
                success: function(resp){
                    console.log('resp', resp);
                        if(resp.length == 0){
                            Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: "Service Not Found",
                            showConfirmButton: true,
                            // timer: 1500
                        });
                    }
                    $('#getMitra').addClass('d-none');
                }
            })
        }

    </script>
@endpush
