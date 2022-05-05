@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Outlet_List'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">  <!-- Page Heading -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Outlets')}}</li>
        </ol>
    </nav>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row flex-between justify-content-between align-items-center flex-grow-1">
                        <div>
                            <h5 class="flex-between">
                                <div>{{\App\CPU\translate('outlet_table')}}</div>
                                <div style="color: red; padding: 0 .4375rem;">({{ $shops->total() }})</div>
                            </h5>
                        </div>
                        <div style="width: 40vw">
                            <!-- Search -->
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-flush">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                           placeholder="{{\App\CPU\translate('Search_Outlet_Name')}}" aria-label="Search orders"
                                           value="{{ $search }}" required>
                                    <input type="hidden" value="{{ $request_status }}" name="status">
                                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                        <div>
                            <a href="{{route('admin.product.outlet-add')}}" class="btn btn-primary  float-right">
                                <i class="tio-add-circle"></i>
                                <span class="text">{{\App\CPU\translate('Add_new_outlet')}}</span>
                            </a>
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
                                <th>{{\App\CPU\translate('oulet_Name')}}</th>
                                <th>{{\App\CPU\translate('oulet_image')}}</th>
                                <th>{{\App\CPU\translate('Address')}}</th>
                                <th>{{\App\CPU\translate('Capacity')}}</th>
                                <th>{{\App\CPU\translate('Chair')}}</th>
                                <th>{{\App\CPU\translate('Active')}} {{\App\CPU\translate('status')}}</th>
                                <th style="width: 5px" class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shops as $k=>$p)
                                <tr>
                                    <th scope="row">{{$shops->firstItem()+$k}}</th>
                                    <td>
                                        <a href="{{route('admin.product.view',[$p['id']])}}">
                                            {{\Illuminate\Support\Str::limit($p['name'],20)}}
                                        </a>
                                    </td>
                                    <td>
                                        <img style="height: 80px; width: auto; max-width: 120px;"
                                            onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset("storage/outlet")}}/{{$p['image']}}"
                                        alt="Outlet image">
                                    </td>
                                    <td>
                                        {{ $p->address }}
                                    </td>
                                    <td>
                                        {{ $p->capacity }} Mitra
                                    </td>
                                    <td>
                                        {{ $p->chair }} Chairs
                                    </td>
                                    <td>
                                        <label class="switch switch-status">
                                            <input type="checkbox" class="status"
                                                   id="{{$p['id']}}" {{$p->status == 1?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="{{route('admin.product.outlet-edit',[$p['id']])}}">
                                            <i class="tio-edit"></i>{{\App\CPU\translate('Edit')}}
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="javascript:"
                                            onclick="form_alert('product-{{$p['id']}}','Want to delete this item ?')">
                                            <i class="tio-add-to-trash"></i> {{\App\CPU\translate('Delete')}}
                                        </a>
                                        <form action="{{route('admin.product.outlet-delete',[$p['id']])}}"
                                            method="post" id="product-{{$p['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{$shops->links()}}
                </div>
                @if(count($shops)==0)
                    <div class="text-center p-4">
                        <img class="mb-3" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                        <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.product.outlet-status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    }
                    else if(data.success == false) {
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        function featured_status(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.product.featured-status')}}",
                method: 'POST',
                data: {
                    id: id
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Featured status updated successfully')}}');
                }
            });
        }

    </script>
@endpush
