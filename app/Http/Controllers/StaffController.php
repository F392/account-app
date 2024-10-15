<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class StaffController extends Controller
{
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

    public function show()
    {
        //従業員一覧を取得
        $crew_names = Crew::where('store_id',session('store_id'))
        ->where('delete_flag',0)
        ->where('perfectly_delete_flag','!=',1)
        ->orderBy('id')
        ->get();
        //セレクトボックスの年を取得
        list($year_array, $now_year) = $this->year();
        //グラフの初期条件は今の年＋店
        $inputs = [];
        $inputs['crew_id'] = 0;
        $inputs['year'] = $now_year;
        $selected_crew_name = '店';
        list($json_year_month, $json_amount, $json_mukei_amount, $json_daily_payment) = $this->chart_date($inputs);

        return view('staff', [
            'crew_names' => $crew_names,
            'selected_crew_id' => $inputs['crew_id'],
            'year_array' => $year_array,
            'selected_year' => $now_year,
            'json_year_month' => $json_year_month,
            'json_amount' => $json_amount,
            'json_mukei_amount' => $json_mukei_amount,
            'selected_crew_name' => $selected_crew_name,
            'json_daily_payment' => $json_daily_payment,
        ]);
    }

    public function chart_date(array $inputs)
    {
        $month_list = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        //データを入れる多次元配列の用意
        $data = array();
        //1月~12月まですべて0円の初期多次元配列データを生成
        //初期化データを用意しないと、データのない月が抽出されずグラフに0円と表示されないため
        for ($i = 0; $i < count($month_list); $i++):
            $data[] = [
                'year_month' => $inputs['year'] . '-' . $month_list[$i],
                'amount' => '0',
                'mukei_amount' => '0',
                'daily_payment' => '0',
            ];
        endfor;
        //売上データ
        $monthly_bills = DB::select("SELECT LEFT(date, 7) as month,
        COUNT(id) as count,
        SUM(intax_bill) as sum
        FROM `customer_bills`
        where crew_id = ? and date LIKE ?
        GROUP BY month
        ORDER BY month ASC", [$inputs['crew_id'], $inputs['year'] . '%']);
        foreach ($monthly_bills as $monthly_bill) {
            //データを入れている初期多次元配列から年月「YYYY-mm」が一致する配列番号を取得
            $key = array_search($monthly_bill->month, array_column($data, 'year_month'));
            //上記で取得した$data[番目]のkeym名:amountの値を抽出したデータに書き換え
            $data[$key]['amount'] = $monthly_bill->sum ?: 0;
        }
        //無形売上データ
        $monthly_mukei_bills = DB::select("SELECT LEFT(customer_bills.date, 7) as month,
        COUNT(mukei_bills.id) as count,
        SUM(mukei_bills.mukei_bill) as sum
        FROM `mukei_bills`
        LEFT JOIN `customer_bills`
        ON customer_bills.id = mukei_bills.customer_bill_id
        where mukei_bills.mukei_crew_id = ? and customer_bills.date LIKE ?
        GROUP BY month
        ORDER BY month ASC", [$inputs['crew_id'], $inputs['year'] . '%']);
        foreach ($monthly_mukei_bills as $monthly_mukei_bill) {
            //データを入れている初期多次元配列から年月「YYYY-mm」が一致する配列番号を取得
            $key = array_search($monthly_mukei_bill->month, array_column($data, 'year_month'));
            //上記で取得した$data[番目]のkeym名:amountの値を抽出したデータに書き換え
            $data[$key]['mukei_amount'] = $monthly_mukei_bill->sum ?: 0;
        }
        //売上データ
        $daily_payments = DB::select("SELECT LEFT(date, 7) as month,
                COUNT(id) as count,
                SUM(bill) as sum
                FROM daily_payments
                where crew_id = ? and date LIKE ? and JSON_CONTAINS(comment, ?, '$.comment_id')
                GROUP BY month
                ORDER BY month ASC", [$inputs['crew_id'], $inputs['year'] . '%', '"0"']);
        foreach ($daily_payments as $daily_payment) {
            //データを入れている初期多次元配列から年月「YYYY-mm」が一致する配列番号を取得
            $key = array_search($daily_payment->month, array_column($data, 'year_month'));
            //上記で取得した$data[番目]のkeym名:amountの値を抽出したデータに書き換え
            $data[$key]['daily_payment'] = $daily_payment->sum ?: 0;
        }
        //JSON形式にする配列を用意
        $year_month = [];
        $sum = [];
        $mukei_sum = [];
        $daily_payment_sum = [];
        //連想配列をfor文で回し、それぞれの配列にセットする
        for ($i = 0; $i < count($month_list); $i++):
            //「YYYY-mm」形式を「YYYY年mm月」形式に変換して配列に追加
            $year_month[] = str_replace('-', '年', $data[$i]['year_month']) . '月';
            //合計金額を配列に追加
            $sum[] = $data[$i]['amount'] ?: 0;
            $mukei_sum[] = $data[$i]['mukei_amount'] ?: 0;
            $daily_payment_sum[] = $data[$i]['daily_payment'] ?: 0;
        endfor;
        //json形式にエンコード
        $json_year_month = json_encode($year_month);
        $json_amount = json_encode($sum);
        $json_mukei_amount = json_encode($mukei_sum);
        $json_daily_payment = json_encode($daily_payment_sum);

        return [$json_year_month, $json_amount, $json_mukei_amount, $json_daily_payment];
    }

    public function search(Request $request)
    {
        //従業員一覧を取得
        $crew_names = Crew::where('store_id',session('store_id'))
        ->where('delete_flag',0)
        ->where('perfectly_delete_flag','!=',1)
        ->orderBy('id')
        ->get();
        //セレクトボックスの年を取得
        list($year_array, $now_year) = $this->year();
        //グラフデータを取得
        $inputs = $request->all();
        list($json_year_month, $json_amount, $json_mukei_amount, $json_daily_payment) = $this->chart_date($inputs);
        //選択された従業員の名前を取得
        $selected_crew_name = Crew::where('id', '=', $inputs['crew_id'])->get('name')[0]['name'] ?? '店';

        return view('staff', [
            'json_year_month' => $json_year_month,
            'json_amount' => $json_amount,
            'json_mukei_amount' => $json_mukei_amount,
            'crew_names' => $crew_names,
            'year_array' => $year_array,
            'selected_year' => $inputs['year'],
            'selected_crew_name' => $selected_crew_name,
            'selected_crew_id' => $inputs['crew_id'],
            'json_daily_payment' => $json_daily_payment,
        ]);
    }

    public function DailyBill(Request $request)
    {
        $inputs = $request->all();
        //DB用にdateを整形
        $inputs['date'] = str_replace('年', '-', $inputs['date']);
        $inputs['date'] = str_replace('月', '', $inputs['date']);
        $year = date('Y', strtotime($inputs['date']));
        $month = date('m', strtotime($inputs['date']));
        //この月の月末日を取得
        $end_day = Carbon::create($year, $month)->endOfMonth()->format('d');
        //日にち生成
        $day_list = [];
        for ($i = 1; $i <= $end_day; $i++) {
            array_push($day_list, $i);
        };
        for ($i = 0; $i < count($day_list); $i++):
            $daily_data[] = [
                'day' => $day_list[$i],
                'daily_amount' => '0',
                'daily_mukei_amount' => '0',
                'daily_payment_detail' => '0',
                'daily_payment_comment' => '',
            ];
        endfor;
        //日毎の売上データ
        $daily_bills = DB::select("SELECT
        SUM(intax_bill) as sum ,
        date
        FROM `customer_bills`
        where crew_id = ? and date LIKE ?
        GROUP BY date
        ORDER BY date ASC;", [$inputs['selected_crew_id'], $inputs['date'] . '%']);
        foreach ($daily_bills as $daily_bill) {
            $key = array_search(date('j', strtotime($daily_bill->date)), array_column($daily_data, 'day'));
            $daily_data[$key]['daily_amount'] = $daily_bill->sum ?: 0;
        }
        //日毎の無形データ
        $daily_mukei_bills = DB::select("SELECT
        SUM(mukei_bills.mukei_bill) as sum,
        date
        FROM `mukei_bills`
        LEFT JOIN `customer_bills`
        ON customer_bills.id = mukei_bills.customer_bill_id
        where mukei_bills.mukei_crew_id = ? and customer_bills.date LIKE ?
        GROUP BY date
        ORDER BY date ASC", [$inputs['selected_crew_id'], $inputs['date'] . '%']);
        foreach ($daily_mukei_bills as $daily_mukei_bill) {
            $key = array_search(date('j', strtotime($daily_mukei_bill->date)), array_column($daily_data, 'day'));
            $daily_data[$key]['daily_mukei_amount'] = $daily_mukei_bill->sum ?: 0;
        }
        //給引きデータ
        $daily_payment_details = DB::select("SELECT
               bill,
               date,
               comment
               FROM `daily_payments`
               where crew_id = ? and date LIKE ? 
               ORDER BY date ASC;", [$inputs['selected_crew_id'], $inputs['date'] . '%']);
        foreach ($daily_payment_details as $daily_payment_detail) {
            $key = array_search(date('j', strtotime($daily_payment_detail->date)), array_column($daily_data, 'day'));
            $daily_data[$key]['daily_payment_detail'] = $daily_payment_detail->bill ?: 0;
        }
        foreach ($daily_payment_details as $daily_payment_detail) {
            $key = array_search(date('j', strtotime($daily_payment_detail->date)), array_column($daily_data, 'day'));
            if(json_decode($daily_payment_detail->comment,true)['comment_id'] == 0){
                $daily_data[$key]['daily_payment_comment'] = '日払い';
            }else{
                $daily_data[$key]['daily_payment_comment'] = json_decode($daily_payment_detail->comment,true)['comment']??'';
            }
        }
        return response()->json($daily_data);
    }
}
