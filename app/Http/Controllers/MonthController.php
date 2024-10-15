<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Crew;
use Illuminate\Support\Facades\DB;

class MonthController extends Controller
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

    public function show(){
        list($year_array, $now_year) = $this->year();
        $selected_month = Carbon::now()->format('m');
        $year_month = $now_year.'-'.$selected_month;
        list($json_crew_name, $json_amount, $json_mukei_amount,$data) = $this->chart_date($year_month);
        //dataを大きい順に並べる
        $nums = array_column($data,'amount');
        $unique_nums = array_unique($nums);
        rsort($unique_nums);
        return view('month', [
            'year_array' => $year_array,
            'selected_year' => $now_year,
            'month_array' => $this->month_array,
            'selected_month' => $selected_month,
            'json_crew_name' => $json_crew_name,
            'json_amount' => $json_amount,
            'json_mukei_amount' => $json_mukei_amount,
            'data' => $data,
            'sort_amount'=>$unique_nums,
        ]);
    }

    public function chart_date($year_month)
    {
        $crew = Crew::where('store_id',session('store_id'))
        ->where('delete_flag',0)
        ->where('perfectly_delete_flag','!=',1)
        ->orderBy('id')
        ->get();
        //データを入れる多次元配列の用意
        $data = array();
        //1月~12月まですべて0円の初期多次元配列データを生成
        //初期化データを用意しないと、データのない月が抽出されずグラフに0円と表示されないため
        for ($i = 0; $i < count($crew); $i++):
            $data[] = [
                'crew_id' => $crew[$i]->id,
                'crew_name' => $crew[$i]->name,
                'amount' => '0',
                'mukei_amount' =>'0'
            ];
        endfor;
        //crew_id=0(店)を追加
        array_push($data,array(
            'crew_id' => 0,
            'crew_name' => '店',
            'amount' => '0',
            'mukei_amount' =>'0'
        ));
        //売上データ
        $crew_bills = DB::select("SELECT 
        crew_id,
        COUNT(id) as count,
        SUM(intax_bill) as sum
        FROM `customer_bills`
        where date LIKE ?
        GROUP BY crew_id", [$year_month . '%']);
        foreach ($crew_bills as $crew_bill) {
            //データを入れている初期多次元配列から年月「YYYY-mm」が一致する配列番号を取得
            $key = array_search($crew_bill->crew_id, array_column($data, 'crew_id'));
            //上記で取得した$data[番目]のkeym名:amountの値を抽出したデータに書き換え
            $data[$key]['amount'] = $crew_bill->sum?:0;
        }
        //無形売上データ
        $mukei_crew_bills = DB::select("SELECT 
        mukei_bills.mukei_crew_id,
        COUNT(mukei_bills.id) as count,
        SUM(mukei_bills.mukei_bill) as sum
        FROM `mukei_bills`
        LEFT JOIN `customer_bills` 
        ON customer_bills.id = mukei_bills.customer_bill_id
        where customer_bills.date LIKE ?
        GROUP BY mukei_bills.mukei_crew_id", [$year_month . '%']);
        foreach ($mukei_crew_bills as $mukei_crew_bill) {
            //データを入れている初期多次元配列から年月「YYYY-mm」が一致する配列番号を取得
            $key = array_search($mukei_crew_bill->mukei_crew_id, array_column($data, 'crew_id'));
            //上記で取得した$data[番目]のkeym名:amountの値を抽出したデータに書き換え
            $data[$key]['mukei_amount'] = $mukei_crew_bill->sum?:0;
        }
        //JSON形式にする配列を用意
        $crew_name = [];
        $sum = [];
        $mukei_sum = [];
        //連想配列をfor文で回し、それぞれの配列にセットする
        for ($i = 0; $i < count($data); $i++):
            //「YYYY-mm」形式を「YYYY年mm月」形式に変換して配列に追加
            $crew_name[] = $data[$i]['crew_name'];
            //合計金額を配列に追加
            $sum[] = $data[$i]['amount']?:0;
            $mukei_sum[] = $data[$i]['mukei_amount']?:0;
        endfor;
        //json形式にエンコード
        $json_crew_name= json_encode($crew_name);
        $json_amount = json_encode($sum);
        $json_mukei_amount = json_encode($mukei_sum);

        return [$json_crew_name, $json_amount, $json_mukei_amount,$data];
    }

    public function search(Request $request){
        list($year_array, $now_year) = $this->year();
        $selected_month = $request->month;
        $selected_year = $request->year;
        $year_month = $request->year.'-'.$selected_month;
        list($json_crew_name, $json_amount, $json_mukei_amount,$data) = $this->chart_date($year_month);
        //dataを大きい順に並べる
        $nums = array_column($data,'amount');
        $unique_nums = array_unique($nums);
        rsort($unique_nums);
        return view('month', [
            'year_array' => $year_array,
            'selected_year' => $selected_year,
            'month_array' => $this->month_array,
            'selected_month' => $selected_month,
            'json_crew_name' => $json_crew_name,
            'json_amount' => $json_amount,
            'json_mukei_amount' => $json_mukei_amount,
            'data' => $data,
            'sort_amount'=>$unique_nums,
        ]);
    }
}

