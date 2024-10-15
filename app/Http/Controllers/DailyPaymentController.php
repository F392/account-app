<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\DailyPayment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Log;
use Illuminate\Support\Facades\Auth;

class DailyPaymentController extends Controller
{
    public $month_array = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

    public function year()
    {
        //年の設定(2020-現在)
        $year_array = [];
        $now = Carbon::now();
        $now_year = $now->format('Y');
        for ($i = 1; $i <= $now_year - 2020; $i++) {
            array_push($year_array, 2020 + $i);
        }
        return [$year_array, $now_year];
    }

    public function show(Request $request)
    {
        list($year_array, $now_year) = $this->year();
        //従業員
        $selected_crew_id = $request->input('crew_id') ?? 0;
        // 当月を取得
        $year = $request->input('year') ?? Carbon::today()->format('Y');
        $month = $request->input('month') ?? Carbon::today()->format('m');
        $thisMonth = Carbon::Create($year, $month, 01, 00, 00, 00);
        // 前月を取得
        $previousMonth = $thisMonth->copy()->subMonth();
        // 翌月を取得
        $nextMonth = $thisMonth->copy()->addMonth();

        // 当月の期間を取得
        $thisMonthPeriod = $this->getThisMonthPeriod($thisMonth);
        // 前月の期間を取得
        $previousMonthPeriod = $this->getPreviousMonthPeriod($thisMonth, $previousMonth);
        // 翌月の期間を取得
        $nextMonthPeriod = $this->getNextMonthPeriod($thisMonth, $nextMonth);

        //従業員名簿取得
        $crew = Crew::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->get();
        //日払い情報取得
        if($selected_crew_id == 0){
            $daily_payments =DailyPayment::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where("date", "LIKE", $year.'-'.$month.'%')->get()->toArray();
        }else{
            $daily_payments =DailyPayment::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('crew_id',$selected_crew_id)->where("date", "LIKE", $year.'-'.$month.'%')->get()->toArray();
        }
        //合計金額
        $monthly_sum = 0;
        foreach($daily_payments as $daily_payment){
            $monthly_sum = $monthly_sum + $daily_payment['bill'];
        }
        $monthly_sum = number_format($monthly_sum);
        //合計件数
        $monthly_number = count($daily_payments);

        return view('DailyPayment', [
            'thisMonth' => $thisMonth,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
            'thisMonthPeriod' => $thisMonthPeriod,
            'previousMonthPeriod' => $previousMonthPeriod,
            'nextMonthPeriod' => $nextMonthPeriod,
            'crew' => $crew,
            'selected_crew_id' => $selected_crew_id,
            'daily_payments' => $daily_payments,
            'year_array' => $year_array,
            'selected_year' => $year,
            'month_array' => $this->month_array,
            'selected_month' => $month,
            'monthly_sum' => $monthly_sum,
            'monthly_number' => $monthly_number,
        ]);
    }

    /**
     * 当月の期間を取得
     */
    private function getThisMonthPeriod($thisMonth)
    {
        // 月初を取得
        $start = $thisMonth->copy()->startOfMonth();
        // 月末を取得
        $end = $thisMonth->copy()->endOfMonth();
        // 月初～月末の期間を取得
        return CarbonPeriod::create($start->toDateString(), $end->toDateString())->toArray();
    }

    /**
     * 前月の期間を取得
     */
    private function getPreviousMonthPeriod($thisMonth, $previousMonth)
    {
        // 当月の月初が日曜日ならArrayは空で
        if ($thisMonth->copy()->startOfMonth()->isSunday()) {
            return [];
        }
        // 前月の月末から始めて、日曜日になるまで日付を減らしていく
        $start = $previousMonth->copy()->endOfMonth();
        while (!$start->isSunday()) {
            $start->subDays();
        }
        // 前月の月末を取得
        $end = $previousMonth->copy()->endOfMonth();
        // 期間を取得
        return CarbonPeriod::create($start->toDateString(), $end->toDateString())->toArray();
    }

    /**
     * 翌月の期間を取得
     */
    private function getNextMonthPeriod($thisMonth, $nextMonth)
    {
        // 当月の月末が土曜日ならArrayは空で
        if ($thisMonth->copy()->endOfMonth()->isSaturday()) {
            return [];
        }
        // 翌月の月初を取得
        $start = $nextMonth->copy()->startOfMonth();
        // 翌月の月初から始めて、土曜日になるまで日付を増やしていく
        $end = $nextMonth->copy()->startOfMonth();
        while (!$end->isSaturday()) {
            $end->addDays();
        }
        // 期間を取得
        return CarbonPeriod::create($start->toDateString(), $end->toDateString())->toArray();
    }

    public function add(Request $request)
    {
        if (DailyPayment::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('crew_id', $request->crew_id)->where('date', $request->date)->count() > 0) {
            session()->flash('flash.error', 'この情報は既に登録されています。');
        } else {
            $daily_payment = new DailyPayment;
            $daily_payment->company_id = Auth::user()->company_id;
            $daily_payment->store_id = session('store_id');
            $daily_payment->crew_id = $request->crew_id;
            $daily_payment->bill = (int) str_replace(',', '', $request->bill);
            $daily_payment->date = $request->date;
            if($request->comment_id == 0){
                $daily_payment->comment = [
                    'comment_id' => $request->comment_id,
                    'comment' => null,
                ];
            }else{
                $daily_payment->comment = [
                    'comment_id' => $request->comment_id,
                    'comment' => $request->comment?? null,
                ];
            }
            $daily_payment->save();
            session()->flash('flash.success', '登録に成功しました。');
        }
        return redirect()->route('DailyPayment');
    }

    public function detail(Request $request)
    {
        Log::debug($request);
        $list = DailyPayment::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('date', $request->date)->get()->toArray();
        //従業員のidを名前に変えるために
        $crew = Crew::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->get()->toArray();
        return response()->json([$list, $crew]);
    }

    public function delete(Request $request)
    {
        DailyPayment::where('id', $request->id)->delete();
        return redirect()->route('DailyPayment');
    }

    public function EditList(Request $request)
    {
        $edit_list = DailyPayment::where('id',$request->id)->get()->toArray();
        return response()->json($edit_list);
    }

    public function edit(Request $request)
    {
        $current = DailyPayment::where('id',$request->current_id)->first();
        if($current->date == $request->edit_date){
            if($request->edit_comment_id == 0){
                $current->fill([
                    'crew_id' => $request->edit_crew_id,
                    'bill' => (int) str_replace(',', '', $request->edit_bill),
                    'date' => $request->edit_date,
                    'comment' => [
                        'comment_id' => $request->edit_comment_id,
                        'comment' => null,
                    ]
                ]);
            }else{
                $current->fill([
                    'crew_id' => $request->edit_crew_id,
                    'bill' => (int) str_replace(',', '', $request->edit_bill),
                    'date' => $request->edit_date,
                    'comment' => [
                        'comment_id' => $request->edit_comment_id,
                        'comment' => $request->edit_comment ?? null,
                    ]
                ]);
            }
            $current->save();
            session()->flash('flash.success', '編集に成功しました。');
        }else{
            if (DailyPayment::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('crew_id', $request->edit_crew_id)->where('date', $request->edit_date)->count() > 0) {
                session()->flash('flash.error', 'この情報は既に登録されています。');
            } else {
                if($request->edit_comment_id == 0){
                    $current->fill([
                        'crew_id' => $request->edit_crew_id,
                        'bill' => (int) str_replace(',', '', $request->edit_bill),
                        'date' => $request->edit_date,
                        'comment' => [
                            'comment_id' => $request->edit_comment_id,
                            'comment' => null,
                        ]
                    ]);
                }else{
                    $current->fill([
                        'crew_id' => $request->edit_crew_id,
                        'bill' => (int) str_replace(',', '', $request->edit_bill),
                        'date' => $request->edit_date,
                        'comment' => [
                            'comment_id' => $request->edit_comment_id,
                            'comment' => $request->edit_comment ?? null,
                        ]
                    ]);
                }
                $current->save();
                session()->flash('flash.success', '編集に成功しました。');
            }
        }
        return redirect()->route('DailyPayment');
    }
}
