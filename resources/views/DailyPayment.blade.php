<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/DailyPayment.css') }}">
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
            <div class="header">
                <div>
                    <div class="seach">
                        <form action="{{ route('DailyPayment') }}">
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
                            @if ($selected_crew_id == 0)
                                <select class="crew_name" name="crew_id" id="crew">
                                    <option value="0" selected>-----</option>
                                    @foreach ($crew as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select class="crew_name" name="crew_id" id="crew">
                                    <option value="0" selected>-----</option>
                                    @foreach ($crew as $item)
                                        @if ($item->id == $selected_crew_id)
                                            <option value="{{ $item->id }}" selected>{{ $item->name }}
                                            </option>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                            <input class="button" value="表示" type="submit"></input>
                        </form>
                    </div>
                </div>
                <div class="add_icon_container">
                    <div>
                        <a href="javascript:void(0)" id="show-add" class="dli-plus-circle">
                            <i><img src="{{ asset('/img/add.png') }}" alt="add"></i>
                            <p>追加</p>
                        </a>
                    </div>
                    <div class="sum_container">
                        <span>月間合計金額</span>
                        <div>
                            <span>{{ $monthly_sum }}</span>
                            <span>円</span>
                        </div>
                    </div>
                    <div class="sum_container">
                        <span>月間合計件数</span>
                        <div>
                            <span>{{ $monthly_number }}</span>
                            <span>件</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="calender_header">
                    <a
                        href="{{ url()->current() . '?year=' . $previousMonth->format('Y') . '&month=' . $previousMonth->format('m').'&crew_id='. $selected_crew_id}}">前月</a>
                    <div>{{ $thisMonth->format('Y年m月') }}</div>
                    <a
                        href="{{ url()->current() . '?year=' . $nextMonth->format('Y') . '&month=' . $nextMonth->format('m').'&crew_id='. $selected_crew_id }}">翌月</a>
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
                        <div class="day this-month"
                            @if (in_array($value->format('Y-m-d'), array_column($daily_payments, 'date'))) id="daily_payment" data-today="{{ $value->format('Y-m-d') }}" data-url="{{ route('DailyPayment_detail') }}" @endif>
                            <p>{{ $value->format('j') }}</p>
                            @if (in_array($value->format('Y-m-d'), array_column($daily_payments, 'date')))
                                @php
                                    $key_lists = array_keys(
                                        array_column($daily_payments, 'date'),
                                        $value->format('Y-m-d'),
                                    );
                                @endphp
                                @foreach ($key_lists as $key_list)
                                    <span
                                        class="daily_payment_crew">{{ $crew->where('id', $daily_payments[$key_list]['crew_id'])->first()->name }}</span>
                                @endforeach
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

        @include('modal.DailyPayment.add')
        @include('modal.DailyPayment.payment')
        @include('modal.DailyPayment.edit')

    </div>

    <!--フラッシュメッセージ-->
    @if (session('flash'))
        @foreach (session('flash') as $key => $item)
            <div class="flash flash-{{ $key }}">{{ session('flash.' . $key) }}</div>
        @endforeach
    @endif

    <script src="{{ asset('/js/DailyPayment.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
