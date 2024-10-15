<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/css/staff.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>売上計上</title>
</head>


<body>

    <div style="display: flex;">

        <div>
            @include('layouts.sidebar')
        </div>

        <div style="width:100%;">

            <div class="seach">
                <form action="{{ route('staff_search') }}">
                    @if ($selected_crew_id == 0)
                        <select class="crew_name" name="crew_id">
                            <option value="0" selected>店</option>
                            @foreach ($crew_names as $crew_name)
                                <option value="{{ $crew_name->id }}">{{ $crew_name->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <select class="crew_name" name="crew_id">
                            <option value="0" selected>店</option>
                            @foreach ($crew_names as $crew_name)
                                @if ($crew_name->id == $selected_crew_id)
                                    <option value="{{ $crew_name->id }}" selected>{{ $crew_name->name }}</option>
                                @else
                                    <option value="{{ $crew_name->id }}">{{ $crew_name->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif

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
                    <input class="button" value="表示" type="submit"></input>
                </form>
            </div>
            <!--ここにグラフを表示-->
            <div class="graph_title">
                <h2>
                    <span>@php echo $selected_year @endphp</span>年
                    <span style="color: rgb(255, 38, 255)">@php echo $selected_crew_name @endphp</span>
                    の売上情報
                </h2>
            </div>
            <div class="bar-graph" id="barGraph">
                <canvas id="canvas"></canvas>
            </div>
        </div>

        @include('modal.staff.daily_bill')

    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script id="staff" type="text/javascript" src="{{ asset('/js/staff.js') }}" data-month='<?php echo $json_year_month; ?>' data-amount='<?php echo $json_amount; ?>' data-mukei='<?php echo $json_mukei_amount; ?>' data-selected_crew_id='<?php echo json_encode($selected_crew_id); ?>' data-click-URL='<?php echo json_encode(route('staff_daily_bill')); ?>' data-selected_crew_name='<?php echo json_encode($selected_crew_name); ?>' data-daily_payment='<?php echo $json_daily_payment; ?>'></script>
</body>
