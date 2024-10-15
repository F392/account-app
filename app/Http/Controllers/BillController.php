<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BillRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Crew;
use App\Models\MukeiBill;
use App\Models\CustomerBill;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Input\Input;
use App\Rules\AlphaRul;
use App\Models\Customer;
use Log;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    /** 
    * テーブルデータを取得
    * 
    */
    public function showList(){
        //売上を入力してない客を取得
        $table = DB::select('SELECT 
        name,company,customers.crew_id,date,customer_bills.id,cash_bill,credit_bill,intax_bill,kake_bill,notax_bill 
        FROM customers JOIN customer_bills ON customers.id = customer_bills.customer_id 
        WHERE customer_bills.flag is null and customers.company_id = ? and customers.store_id = ?',[Auth::user()->company_id,session('store_id')]);
        //従業員一覧を取得
        $crew_names = Crew::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->get();

        //計上可能なデータがなけれべ表示
        if(empty($table)){
            Session::flash('err_msg', '計上可能なデータはありません。');
        }else{
            Session::forget('err_msg');
        }

        return view('bill',[
            'table' => $table,
            'crew_names' => $crew_names
        ]);
    }

    /** 
    * 登録ボタンを押下した時の処理(DB更新)
    *
    */
    public function save(BillRequest $request)
    {
        $inputs = $request->all();
        //どの回答のsubmitか
        $key = $inputs['key'];
        //カンマを削除
        $inputs['cash_bill'.$key] = (int)str_replace(',', '',$inputs['cash_bill'.$key]);
        $inputs['credit_bill'.$key] = (int)str_replace(',', '',$inputs['credit_bill'.$key]);
        $inputs['kake_bill'.$key] = (int)str_replace(',', '',$inputs['kake_bill'.$key]);
        $inputs['intax_bill'.$key] = (int)str_replace(',', '',$inputs['intax_bill'.$key]);
        $inputs['notax_bill'.$key] = (int)str_replace(',', '',$inputs['notax_bill'.$key]);
        for( $i=0; $i<count($inputs['mukei_bill']); $i++){
            $inputs['mukei_bill'][$i] = str_replace(',', '',$inputs['mukei_bill'][$i]);
        }
        //掛けがあるかないかでflagを分ける
        if( $inputs['kake_bill'.$key]=="" or $inputs['kake_bill'.$key] == '0'){
            $flag = 1;
        }else{
            $flag = 2;
        }
        
        //DB更新
        DB::beginTransaction();
        try {
            //掛け、クレジット、現金の合計金額が税込金額と一致しなければエラー
            if($inputs['cash_bill'.$key] + $inputs['credit_bill'.$key] + $inputs['kake_bill'.$key] != $inputs['intax_bill'.$key]){
                throw new \Exception('支払い方法の合計金額と売上が異なります。');
            }
            // customer_billを更新
            $bill = CustomerBill::find($inputs['bill_id'.$key]);
            $bill->fill([
                'crew_id' => $inputs['crew_id'.$key],
                'cash_bill' => $inputs['cash_bill'.$key]?:null,
                'credit_bill' => $inputs['credit_bill'.$key]?:null,
                'intax_bill' => $inputs['intax_bill'.$key],
                'notax_bill' => $inputs['notax_bill'.$key],
                'kake_bill' => $inputs['kake_bill'.$key]?:null,
                'flag' => $flag,
            ]);
            $bill->save();

            //無形のあれこれ(複雑になっちゃった)
            if(count($inputs['mukei_crew'])==1 and $inputs['mukei_crew'][0]==null and $inputs['mukei_bill'][0]==null){
            }else{
                for($i = 0; $i < count($inputs['mukei_crew']); $i++){
                    //値がどちらかがnullだったらエラー
                    if(empty($inputs['mukei_crew'][$i]) xor empty($inputs['mukei_bill'][$i])){
                        throw new \Exception('無形売上の入力に誤りがあります。');
                    }
                    //mukei_billに無形情報を入れる(値が両方ある時のみ登録)
                    else if(isset($inputs['mukei_crew'][$i]) and isset($inputs['mukei_bill'][$i])){
                        $mukei = new MukeiBill;
                        $mukei->mukei_crew_id = $inputs['mukei_crew'][$i];
                        $mukei->mukei_bill = $inputs['mukei_bill'][$i];
                        $mukei->customer_bill_id = $inputs['bill_id'.$key];
                        $mukei->save();
                    }
                }
            }
            DB::commit();
            session()->flash('flash.success', '登録に成功しました。');
        } catch(\Throwable $e) {
            DB::rollback();
            if($e->getMessage()){
                session()->flash('flash.error', $e->getMessage());
            }else{
                session()->flash('flash.error', '登録に失敗しました。');
            }
            
        }

        return redirect()->route('bill');
    }

    public function search(){
        $user = '';
        return response()->json($user);
    }

    public function add(Request $request){
        //条件に合わせてデータ取得
        $query = Customer::query();
        //店を選択した時、id=0を再定義しないとバグる(原因不明)
        if($request['crew'] === '0'){
            $conditions = [
                'kana' => $request['kana']?:null,
                'company' => $request['company']?:null,
                'crew_id' => 0,
            ];
        }else{
            $conditions = [
                'kana' => $request['kana']?:null,
                'company' => $request['company']?:null,
                'crew_id' => $request['crew']?:null,
            ];
        }
        
        foreach ($conditions as $key => $value) {
            if (isset($value)) {
                if($key == 'crew_id'){
                    $query->where($key,$value);
                }else{
                    $query->where($key, "LIKE", "%$value%");
                }
            }
        }
        
        $user = $query->get();

        //従業員のidを名前に変えるために
        $crew_names = Crew::all();

        return [$user,$crew_names];
    }


public function SaveNewBill(Request $request){
    //idから担当を取得
    $crew_id = Customer::find($request->id);
    //新しくデータを挿入
    DB::table('customer_bills')->insert(
        [
            'customer_id' => $request->input('id'),
            'crew_id' => $crew_id['crew_id'],
            'date' => $request->input('date'),
        ]
    );
    session()->flash('flash.success', '1件の新規データを作成しました。');
}
}