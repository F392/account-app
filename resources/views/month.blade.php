<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/css/month.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <title>売上計上</title>
</head>


<body>

    <div style="display: flex;">

        <div>
            @include('layouts.sidebar')
        </div>

        <div style="width:100%;">

            <div class="seach">
                <form action="{{ route('month_search') }}">
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
                                <option value="{{ $month }}" selected>{{ (int)$month }}月
                                </option>
                            @else
                                <option value="{{ $month }}">{{ (int)$month }}月</option>
                            @endif
                        @endforeach
                    </select>
            
                    <input class="button" value="表示" type="submit"></input>
                </form>
            </div>

            <!--ここにグラフを表示-->
            <div class="graph_title">
                <h2>
                    <span>@php echo $selected_year @endphp</span>年
                    <span>@php echo $selected_month @endphp</span>月
                    の売上情報
                </h2>
            </div>

            <div class="graph_table_container">
                <div class="bar-graph" id="barGraph">
                    <canvas id="canvas"></canvas>
                </div>

                <div class="table_container">
                    <table>
                        <tr>
                            <th></th>
                            <th>名前</th>
                            <th>売上</th>
                            <th>無形売上</th>
                        </tr>
                        @foreach ($data as $key => $item)
                        @if ($sort_amount[0]==$item['amount'])
                        <tr class="gold_crown">
                        @elseif($sort_amount[1]==$item['amount'])
                        <tr class="silver_crown">
                        @elseif($sort_amount[2]==$item['amount'])
                        <tr class="copper_crown">
                        @else
                        <tr>
                        @endif
                        <td>
                            @if ($sort_amount[0]==$item['amount'])
                            <img src="{{ asset('img/gold_crown.png') }}" alt="gold_crown">
                            @elseif($sort_amount[1]==$item['amount'])
                            <img src="{{ asset('img/silver_crown.png') }}" alt="silver_crown">
                            @elseif($sort_amount[2]==$item['amount'])
                            <img src="{{ asset('img/copper_crown.png') }}" alt="copper_crown">
                            @endif
                        </td>
                            <td>
                                {{$item['crew_name']}}
                            </td>
                            <td>
                                {{number_format($item['amount'])}}
                            </td>
                            <td>
                                {{number_format($item['mukei_amount'])}}
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <div class="sum_bill">
                        <span>{{number_format(array_sum(array_column($data,'amount')))}} 円</span>
                    </div>
                </div>


            </div>

        </div>

    </div>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script id="month" type="text/javascript" src="{{ asset('/js/month.js') }}" 
    data-crew='<?php echo $json_crew_name; ?>' 
    data-amount='<?php echo $json_amount; ?>' 
    data-mukei='<?php echo $json_mukei_amount; ?>' ></script>
</body>
