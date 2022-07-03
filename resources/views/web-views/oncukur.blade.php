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

</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <input type="hidden" id="user" value="{{ auth('customer')->id() }}">
        <div class="col-12">
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <h3>Order Service</h3>
                </div>
                <div class="card-body justify-content-center d-flex">
                    <div class="map" id="map" style="width: 90%; height: 500px;">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div>
</div>
@endsection

@push('script')
    <script>

    </script>
@endpush
