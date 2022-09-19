@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Order List'))

@push('css_or_js')
    <style>
        .widget-categories .accordion-heading > a:hover {
            color: #FFD5A4 !important;
        }

        .widget-categories .accordion-heading > a {
            color: #FFD5A4;
        }

        body {
            font-family: 'Titillium Web', sans-serif
        }

        .card {
            border: none
        }

        .totals tr td {
            font-size: 13px
        }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .spandHeadO {
            color: #FFFFFF !important;
            font-weight: 600 !important;
            font-size: 14px;

        }

        .tdBorder {
            border-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1px solid #f7f0f0;
            text-align: center;
        }

        .bodytr {
            text-align: center;
            vertical-align: middle !important;
        }

        .sidebar h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}} !important;
            transition: .2s ease-in-out;
        }

        tr td {
            padding: 3px 5px !important;
        }

        td button {
            padding: 3px 13px !important;
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{$web_config['primary_color']}};
            }

            .orderDate {
                display: none;
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 float-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} headerTitle">{{\App\CPU\translate('my_order')}}</h1>
            </div>
        </div>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 mt-2 col-md-9">
                <div class="card box-shadow-sm">
                    <div style="overflow: auto">
                        <table class="table">
                            <thead>
                            <tr style="background-color: #6b6b6b;">
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO ">{{\App\CPU\translate('Order#')}}</span></div>
                                </td>

                                <td class="tdBorder orderDate">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Date')}}</span>
                                    </div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('Status')}}</span></div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('Payment')}}</span></div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('Total')}}</span></div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('action')}}</span></div>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="bodytr font-weight-bold">
                                        {{\App\CPU\translate('ID')}}: {{$order['id']}}
                                    </td>
                                    <td class="bodytr orderDate"><span class="">{{$order['created_at']}}</span></td>
                                    <td class="bodytr">
                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                            <span class="badge badge-danger text-capitalize">
                                                {{\App\CPU\translate('cancelled')}}
                                            </span>
                                        @elseif($order['order_status']=='confirmed')
                                            <span class="badge badge-success text-capitalize">
                                                {{\App\CPU\translate('processing')}}
                                            </span>
                                        @elseif($order['order_status']=='processing')
                                            <span class="badge badge-success text-capitalize">
                                                {{\App\CPU\translate('processing')}}
                                            </span>
                                        @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-success text-capitalize">
                                                {{\App\CPU\translate('finished')}}
                                            </span>
                                        @elseif($order['order_status']=='pending')
                                            <span class="badge badge-warning text-capitalize">
                                                {{\App\CPU\translate('Pending')}}
                                            </span>
                                        @else
                                            <span class="badge badge-info text-capitalize">
                                                {{\App\CPU\translate($order['order_status'])}}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="bodytr">
                                        @if($order['payment_status']=='paid')
                                            <span class="badge badge-success text-capitalize">
                                                {{\App\CPU\translate('paid')}}
                                            </span>
                                        @else
                                            <span class="badge badge-danger text-capitalize">
                                                {{\App\CPU\translate($order['payment_status'])}}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="bodytr">
                                        {{\App\CPU\Helpers::currency_converter($order['order_amount'])}}
                                    </td>
                                    <td class="bodytr" style="width: 162px">
                                        <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                                            class="btn btn-secondary btn-sm p-1 mb-1">
                                            <i class="fa fa-eye"></i> {{\App\CPU\translate('view')}}
                                        </a>
                                        @if ($order['order_status'] !== 'canceled')
                                            @if ($order['order_status'] !== 'delivered' || $order['payment_status'] !== 'paid')
                                            @php($shop = \App\CPU\Helpers::get_shop($order->seller_id))
                                                        @php($seller = $order->mitra_id)
                                                        @if ($seller !== 0)
                                                        @php($seller = $order->mitra_id)
                                                        @php($receiver = 'Mitra')
                                                    @else
                                                        @php($seller = $order->seller_id)
                                                        @php($receiver = 'Outlet')
                                                    @endif

                                                    <button type="button" class="btn btn-outline-success btn-sm p-1 mb-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $order['id'] }}">
                                                        <i class="fas fa-comments"></i> {{ $receiver }}
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop{{ $order['id'] }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Chat {{ $receiver }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row msg-option" id="msg-option">
                                                                    <form action="" id="chatOwner{{ $order['id'] }}">
                                                                        <input type="hidden" class="receiver" name="receiver" value="{{ $receiver }}">
                                                                        <input type="hidden" class="seller_id" name="seller_id" value="{{ $seller }}" hidden seller-id="{{$seller}}">
                                                                        <input type="hidden" class="shop_id" name="shop_id" value="{{ $shop }}">
                                                                        <textarea shop-id="{{ $shop }}" class="chatInputBox form-control" name="message"
                                                                                    id="chatInputBox" rows="3"></textarea>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="go-to-chatbox" id="go_to_chatbox">
                                                                        <a href="{{route('chat-with-seller')}}" class="btn btn-primary" id="go_to_chatbox_btn">
                                                                            {{\App\CPU\translate('go_to')}} {{\App\CPU\translate('chatbox')}} </a>
                                                                    </div>
                                                                    {{-- <button class="btn btn-secondary" style="color: white;" data-bs-dismiss="modal"
                                                                    id="cancelBtn">{{\App\CPU\translate('cancel')}}
                                                                    </button> --}}
                                                                    <button class="btn btn-primary" style="color: white;" onclick="send({{ $order['id'] }})">{{\App\CPU\translate('send')}}</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                            @endif
                                            @if($order['order_status']=='pending')
                                                <a href="javascript:"
                                                onclick="route_alert('{{ route('order-cancel',[$order->id]) }}','{{\App\CPU\translate('want_to_cancel_this_order?')}}')"
                                                class="btn btn-danger btn-sm top-margin">
                                                    <i class="fa fa-trash"></i> {{\App\CPU\translate('cancel')}}
                                                </a>
                                            @elseif($order['order_status'] == 'delivered' && $order['payment_status'] == 'unpaid')
                                            <a class="btn btn-info btn-sm top-margin" href="{{ route('midtrans-payment.pay', ['id' => $order->id]) }}">
                                                <i class="fa fa-dollar-sign"></i> {{\App\CPU\translate('Pay_now')}}
                                            </a>
                                            @else
                                            @if ($order['order_status'] !== 'processing' && $order['order_status'] !== 'delivered' && $order['order_status'] !== 'confirmed')
                                            <button class="btn btn-danger btn-sm" onclick="cancel_message()">
                                                <i class="fa fa-trash"></i> {{\App\CPU\translate('cancel')}}
                                            </button>
                                            @endif
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($orders->count()==0)
                            <center class="mt-3 mb-2">{{\App\CPU\translate('no_order_found')}}</center>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function cancel_message() {
            toastr.info('{{\App\CPU\translate('order_can_be_canceled_only_when_pending.')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function send(order_id){
            let msgValue = $('#msg-option' + order_id).find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option' + order_id).find('textarea').attr('shop-id'),
                seller_id: $('.msg-option' + order_id).find('.seller_id').attr('seller-id'),
                receiver: $('.msg-option' + order_id).find('.receiver').attr('value'),
            }
            let form = $('#chatOwner' + order_id).serialize();
            console.log('data', form);
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('messages_store')}}',
                    data: form,
                    success: function (respons) {
                        console.log('send successfully');
                    }
                });


            $('#staticBackdrop' + order_id).modal('hide');
            } else {
                console.log('say something');
            }
        }

        $('.sendBtn').on('click', function (e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
                receiver: $('.msg-option').find('.receiver').attr('value'),
            }
            let chat = $('#chatOwner').serialize();
            console.log('data', data);
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // $.ajax({
                //     type: "post",
                //     url: '{{route('messages_store')}}',
                //     data: data,
                //     success: function (respons) {
                //         console.log('send successfully');
                //     }
                // });


                // $('#chatInputBox').val('');
                // $('#msg-option').css('display', 'none');
                // $('#contact-seller').find('.contact').attr('disabled', '');
                // $('#seller_details').animate({'height': '125px'});
                // $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
    </script>
@endpush
