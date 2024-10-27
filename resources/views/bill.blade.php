<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/bill.css') }}">
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


            <div class="add_icon_container">
                <a href="javascript:void(0)" id="show-search" data-url="{{ route('bill_search') }}"
                    class="dli-plus-circle">
                    <i><img src="{{ asset('/img/add.png') }}" alt="add"></i>
                    <p>追加</p>
                </a>
            </div>

            <form action="{{ route('bill_save') }}" method="POST" onSubmit="return checkSubmit()">
                @csrf
                <div class="form_design">
                    <table>
                        @if (count($table) == 0)
                            <tr class="table_header">
                                <th>名前</th>
                                <th>会社名</th>
                                <th>来店日</th>
                                <th>担当</th>
                                <th>売上(税抜)</th>
                                <th>売上(税込)</th>
                                <th>無形売上(税抜)</th>
                                <th>登録</th>
                            </tr>
                        @endif
                        @foreach ($table as $key => $item)
                            @csrf
                            @if ($key == 0)
                                <tr class="table_header">
                                    <th>名前</th>
                                    <th>会社名</th>
                                    <th>来店日</th>
                                    <th>担当</th>
                                    <th>売上</th>
                                    <th>支払い方法(税込)</th>
                                    <th>無形売上(税抜)</th>
                                    <th>登録</th>
                                </tr>
                            @endif
                            <tr class="submit{{ $key }}">
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->company }}</td>
                                <td>{{ $item->date }}</td>
                                <td>
                                    <select name="crew_id{{ $key }}">
                                        <option value="0">店</option>
                                        @foreach ($crew_names as $crew_name)
                                            @if ($crew_name->id == $item->crew_id)
                                                <option value="{{ $crew_name->id }}" selected>{{ $crew_name->name }}
                                                </option>
                                            @else
                                                <option value="{{ $crew_name->id }}">{{ $crew_name->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <p>税抜</p>
                                    <input id="notax_{{ $key }}" value="{{ $item->notax_bill }}"
                                        name="notax_bill{{ $key }}" type="text">
                                    @error('notax_bill' . $key)
                                        <p class="err_msg">{{ $message }}</p>
                                    @enderror
                                    <p>税込</p>
                                    <input id="intax_{{ $key }}" value="{{ $item->intax_bill }}"
                                        name="intax_bill{{ $key }}" type="text">
                                    @error('intax_bill' . $key)
                                        <p class="err_msg">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td>
                                    <p>現金</p>
                                    <input id="cash_{{ $key }}" value="{{ $item->cash_bill }}"
                                        name="cash_bill{{ $key }}" type="text">
                                    @error('cash_bill' . $key)
                                        <p class="err_msg">{{ $message }}</p>
                                    @enderror
                                    <p>クレジット</p>
                                    <input id="credit_{{ $key }}" value="{{ $item->credit_bill }}"
                                        name="credit_bill{{ $key }}" type="text">
                                    @error('credit_bill' . $key)
                                        <p class="err_msg">{{ $message }}</p>
                                    @enderror
                                    <p>掛け</p>
                                    <input id="kake_{{ $key }}" value="{{ $item->kake_bill }}"
                                        name="kake_bill{{ $key }}" type="text">
                                    @error('kake_bill' . $key)
                                        <p class="err_msg">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td>
                                    <div class="clone_target">
                                        <div>
                                            <select name="mukei_crew{{ $key }}[]">
                                                <option value="">-----</option>
                                                @foreach ($crew_names as $crew_name)
                                                    <option value="{{ $crew_name->id }}">{{ $crew_name->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input id="mukei_bill_{{ $key }}"
                                            name="mukei_bill{{ $key }}[]" type="text">
                                    </div>
                                    <div style="text-align: left" onclick="insert_row({{ $key }})">
                                        <span class="dli-plus"><span></span></span>
                                    </div>
                                </td>
                                <input type="hidden" name="bill_id{{ $key }}" value="{{ $item->id }}">
                                <td><button class="button" name="button" value="{{ $key }}"
                                        type="submit">登録</button></td>
                            </tr>
                        @endforeach
                    </table>
            </form>
            @if (session('err_msg'))
                <div class="notable">
                    {{ session('err_msg') }}
                </div>
            @endif

        </div>
    </div>

    @include('modal.bill.search')
    @include('modal.bill.add')

    <!--フラッシュメッセージ-->
    @if (session('flash'))
        @foreach (session('flash') as $key => $item)
            <div class="flash flash-{{ $key }}">{{ session('flash.' . $key) }}</div>
        @endforeach
    @endif

    <script src="{{ asset('/js/bill.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
