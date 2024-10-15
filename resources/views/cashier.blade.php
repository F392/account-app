<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/cashier.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>レジ金</title>
</head>


<body>

    <div style="display: flex;">

        <div>
            @include('layouts.sidebar')
        </div>

        <div style="width:100%;">
            <div class="seach">
                <form action="{{ route('cashier') }}">
                    <select class="year" name="year">
                        @foreach ($year_array as $year)
                            @if ($year == $selected_year)
                                <option value="{{ $year }}" selected>{{ $year }}年
                                </option>
                            @else
                                <option value="{{ $year }}">{{ $year }}年</option>
                            @endif
                        @endforeach
                    </select>
                    <select class="month" name="month">
                        @foreach ($month_array as $month)
                            @if ($month == $selected_month)
                                <option value="{{ $month }}" selected>{{ (int) $month }}月
                                </option>
                            @else
                                <option value="{{ $month }}">{{ (int) $month }}月</option>
                            @endif
                        @endforeach
                    </select>
                    <input class="button" value="表示" type="submit"></input>
                </form>
            </div>
            <div class="container">
                <div class="header">
                    <a
                        href="{{ url()->current() . '?year=' . $previousMonth->format('Y') . '&month=' . $previousMonth->format('m') }}">前月</a>
                    <div>{{ $thisMonth->format('Y年m月') }}</div>
                    <a
                        href="{{ url()->current() . '?year=' . $nextMonth->format('Y') . '&month=' . $nextMonth->format('m') }}">翌月</a>
                </div>
                <div class="grid">
                    {{-- 曜日 --}}
                    @foreach (['日', '月', '火', '水', '木', '金', '土'] as $weekday)
                        <div class="weekday">{{ $weekday }}</div>
                    @endforeach
                    {{-- 前月の期間 --}}
                    @foreach ($previousMonthPeriod as $value)
                        <div class="day not-this-month">{{ $value->format('j') }}</div>
                    @endforeach
                    {{-- 当月の期間 --}}
                    @foreach ($thisMonthPeriod as $value)
                        <div class="day this-month" id="daily_cashier" data-today="{{ $value->format('Y-m-d') }}"
                            data-url="{{ route('cashier_detail') }}"
                            @if (in_array($value->format('Y-m-d'), array_column($cashiers, 'date'))) style='background-color: rgba(125, 125, 125, 0.2);' @endif>
                            {{ $value->format('j') }}
                            @if (in_array($value->format('Y-m-d'), array_column($cashiers, 'date')))
                                <div class="cashier_done">
                                    <span>済</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    {{-- 翌月の期間 --}}
                    @foreach ($nextMonthPeriod as $value)
                        <div class="day not-this-month">{{ $value->format('j') }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('modal.cashier.DailyCashier')

    </div>

    @if (session('flash'))
        @foreach (session('flash') as $key => $item)
            <div class="flash flash-{{ $key }}">{{ session('flash.' . $key) }}</div>
        @endforeach
    @endif

    <script src="{{ asset('/js/cashier.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
