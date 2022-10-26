@php
    $data = \App\CPU\Helpers::lastChat();
    $last_chat = $data[0];
    $chattings = $data[1];
    $unique_shops = $data[2];
@endphp
{{-- {{ dd($last_chat, $chattings, $unique_shops) }} --}}
