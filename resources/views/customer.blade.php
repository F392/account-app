<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/customer.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>顧客別売上</title>
</head>


<body>

    <div style="display: flex;">

        <div>
            @include('layouts.sidebar')
        </div>

        <div style="width:100%;">
            <div class="customer_container">
                <div>
                    <span class="form_label_elective">任意</span>
                    <b>名前&nbsp;:</b>
                </div>
                <div>
                    <input id="customer_name" name="name" type="text">
                </div>
            </div>
            <div class="customer_container">
                <div>
                    <span class="form_label_elective">任意</span>
                    <b>カナ&nbsp;:</b>
                </div>
                <div>
                    <input id="customer_kana" name="kana" type="text">
                </div>
            </div>
            <div class="customer_container">
                <div>
                    <span class="form_label_elective">任意</span>
                    <b>会社名&nbsp;:</b>
                </div>
                <div>
                    <input id="customer_company" name="company" type="text">
                </div>
            </div>
            <div class="customer_container">
                <div>
                    <span class="form_label_elective">任意</span>
                    <b>担当&nbsp;:</b>
                </div>
                <div>
                    <select id="customer_crew_id" name="crew_id" >
                        <option value="">-----</option>
                        <option value="0">店</option>
                        @foreach ($crew as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="customer_container">
                <div>
                    <span class="form_label_elective">任意</span>
                    <b>来店日&nbsp;:</b>
                </div>
                <div>
                    <input id="customer_date" type="date" name="date">
                </div>
            </div>
            
            <div class="customer_submit">
                <input type="hidden" id="customer_url" value="{{route('customer_search')}}">
                <button onclick="search()" class="btn btn_color" type="submit">検索</button>
            </div>
        </div>

    </div>

    @include('modal.customer.search')
    @include('modal.customer.select')
    @include('modal.customer.edit')

    <!--フラッシュメッセージ-->
    @if (session('flash'))
        @foreach (session('flash') as $key => $item)
            <div class="flash flash-{{ $key }}">{{ session('flash.' . $key) }}</div>
        @endforeach
    @endif

    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="{{ asset('/js/customer.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
