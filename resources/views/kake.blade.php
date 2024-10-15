<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/kake.css') }}">
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

        @if (count($kakes) == 0)
            <div style="width:100%;">
                <div class="outer">
                    <div class="inner">
                        現在、この店舗に売掛はありません。
                    </div>
                </div>
            </div>
        @else
            <div style="width: 100%">
                <div class="header"></div>
                <div class="form_design">
                    <table>
                        <tr class="table_header">
                            <th>日付</th>
                            <th>名前</th>
                            <th>会社名</th>
                            <th>支払い担当</th>
                            <th>金額(税込)</th>
                            <th>回収</th>
                        </tr>
                        @foreach ($kakes as $kake)
                            <form action="{{ route('kake_save') }}" method="POST" onSubmit="return checkSubmit()">
                                @csrf
                                <tr>
                                    <td>{{ $kake->date }}</td>
                                    <td>{{ $kake->name }}</td>
                                    <td>{{ $kake->company }}</td>
                                    @php
                                        $keyIndex = array_search($kake->crew_id, array_column($crew, 'id'));
                                        $result = $crew[$keyIndex];
                                    @endphp
                                    <td>{{ $result['name'] }}</td>
                                    <td>{{ number_format($kake->kake_bill) }}</td>
                                    <td><button type="submit">OK</button></td>
                                    <input name="customer_bill_id" type="hidden" value="{{ $kake->id }}">
                                </tr>
                            </form>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif
    </div>

    <script src="{{ asset('/js/kake.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
