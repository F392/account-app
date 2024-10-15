<div class="header">
    <div>
        <div class="seach">
            <form action="{{ route('supplier') }}" method="GET">
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
    </div>

    <div class="add_icon_container">
        <a href="javascript:void(0)" 
        id="addsupplier"
        class="dli-plus-circle">
            <i><img src="{{asset('/img/add.png')}}" alt="add"></i>
            <p>追加</p>
        </a>
        <a href="javascript:void(0)" 
        id="listsupplier"
        data-url="{{ route('supplier_list') }}"
        class="dli-plus-circle">
            <i><img src="{{asset('/img/list.png')}}" alt="list"></i>
            <p>一覧</p>
        </a>
    </div>

</div>

<div class="checkbox_alert">
    <span>※現金支払いの場合は、チェックボックスにチェックを入れてください。</span>
    <br><span>※税込価格を入力してください。</span>
</div>

    <table id="supplier_table">
        <tr class="table_header">
            <th>日付</th>
            @foreach($suppliers as $supplier)
            <th>{{$supplier->name}}</th>
            @endforeach
            <th>日別合計</th>
        </tr>
        @foreach($allDays as $allDay)
        <tr>
            <input value="{{$allDay->format('Y-m-d')}}" type="hidden">
            <td class="td_day">{{(int)$allDay->format('d')}}日</td>
            @php
                $day_key = array_search($allDay->format('Y-m-d'), array_column($lists, 'date'));
            @endphp
            @if ($day_key or $day_key === 0)
            @foreach ($suppliers as $supplier)
                @php
                    $id_key = array_search($supplier->id, array_column($lists[$day_key]->indvBill, 'supplier_id'))??null;
                @endphp
                @if ($id_key or $id_key === 0)
                <td id="{{$allDay->format('d')}}_{{$supplier->id}}"><span><input class="indvBill_{{$supplier->id}}" id="indvBill_{{$allDay->format('d')}}_{{$supplier->id}}" value="{{number_format($lists[$day_key]->indvBill[$id_key]['supplier_each_bill'])}}" type="text"></span>
                    @if ($lists[$day_key]->indvBill[$id_key]['cash_flag'] ==1)
                    <span><input id="cash_flag_{{$allDay->format('d')}}_{{$supplier->id}}" type="checkbox" checked></span>
                    @else
                    <span><input id="cash_flag_{{$allDay->format('d')}}_{{$supplier->id}}" type="checkbox"></span>
                    @endif
                </td>
                @else
                <td id="{{$allDay->format('d')}}_{{$supplier->id}}"><span><input id="indvBill_{{$allDay->format('d')}}_{{$supplier->id}}" type="text"></span>
                    <span><input id="cash_flag_{{$allDay->format('d')}}_{{$supplier->id}}" type="checkbox"></span>
                </td>
                @endif
            @endforeach
            @else
            @foreach ($suppliers as $supplier)
            <td id="{{$allDay->format('d')}}_{{$supplier->id}}"><span><input id="indvBill_{{$allDay->format('d')}}_{{$supplier->id}}" type="text"></span>
                <span><input id="cash_flag_{{$allDay->format('d')}}_{{$supplier->id}}" type="checkbox"></span>
            </td>
            @endforeach
            @endif
            @if ($day_key or $day_key === 0)
            <td class="daily_sum">{{number_format($lists[$day_key]->dailyTotal)}}</td>
            @else
            <td class="daily_sum">0</td>
            @endif
        </tr>
        @endforeach
        {{-- 最下セルに合計を表示 --}}
        <tr>
            <td></td>
            @foreach ($suppliers as $supplier)
            <td id="each_sum_{{$supplier->id}}"></td>
            @endforeach
            <td id="total_sum"></td>
        </tr>
    </table>

