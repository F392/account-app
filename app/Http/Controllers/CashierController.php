<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
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

        //レジ金取得
        $cashiers = Cashier::where('company_id', Auth::user()->company_id)
        ->where('store_id', session('store_id'))
        ->where("date", "LIKE", $year . '-' . $month . '%')
        ->get()->toArray();

        return view('cashier', [
            'year_array' => $year_array,
            'selected_year' => $year,
            'month_array' => $this->month_array,
            'selected_month' => $month,
            'thisMonth' => $thisMonth,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
            'thisMonthPeriod' => $thisMonthPeriod,
            'previousMonthPeriod' => $previousMonthPeriod,
            'nextMonthPeriod' => $nextMonthPeriod,
            'cashiers' => $cashiers,
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

    public function detail(Request $request)
    {
        $no_data = [
            'date' => $request->date,
            'ex_cashier_bill' => '',
        ];
        if (count(Cashier::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('date', $request->date)->get()) == 0) {
            $data = $no_data;
        } else {
            $data = Cashier::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('date', $request->date)->first()->toArray();
        }

        //現金売上
        $cash_bill = DB::select("SELECT
        SUM(customer_bills.cash_bill) as sum
        FROM customer_bills
        JOIN customers
        ON customer_bills.customer_id = customers.id
        where customer_bills.date = ? and customer_bills.flag != 0 and customers.company_id = ? and customers.store_id = ?
        GROUP BY date", [$request->date,Auth::user()->company_id,session('store_id')]);
        if (isset($cash_bill[0]->sum)) {
            $cash_sum = $cash_bill[0]->sum;
        } else {
            $cash_sum = 0;
        }

        //提携業社
        $supplier = DB::select("SELECT
        SUM(bill) as sum
        FROM supplier_bills
        where date = ? and cash_flag = 1 and company_id = ? and store_id = ?
        GROUP BY date", [$request->date,Auth::user()->company_id,session('store_id')]);
        if (isset($supplier[0]->sum)) {
            $supplier_sum = $supplier[0]->sum;
        } else {
            $supplier_sum = 0;
        }

        //日払い
        $daily_payment = DB::select("SELECT 
        SUM(bill) as sum 
        FROM daily_payments
        where date = ? and JSON_CONTAINS(comment, ?, '$.comment_id') and company_id = ? and store_id = ?
        GROUP BY date", [$request->date,'"0"',Auth::user()->company_id,session('store_id')]);

        if (isset($daily_payment[0]->sum)) {
            $daily_payment_sum = $daily_payment[0]->sum;
        } else {
            $daily_payment_sum = 0;
        }

        //営業後レジ金
        if (count(Cashier::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->where('date', $request->date)->get()) == 0) {
            $cashier_bill = $cash_sum - $daily_payment_sum - $supplier_sum;
        } else {
            $cashier_bill = $data['ex_cashier_bill'] + $cash_sum - $daily_payment_sum - $supplier_sum;
        }

        return response()->json([$data , $cash_sum , $supplier_sum , $daily_payment_sum , $cashier_bill]);
    }

    public function save(Request $request){
        //保存
        Cashier::updateOrCreate(
            ['date' => $request->date], 
            ['ex_cashier_bill' => (int)str_replace(',', '', $request->ex_cashier_bill),'company_id'=>Auth::user()->company_id,'store_id'=>session('store_id')],
        );
        session()->flash('flash.success', '保存に成功しました。');
        return redirect()->route('cashier');
    }
}
