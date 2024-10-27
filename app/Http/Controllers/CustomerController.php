<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\CustomerBill;
use App\Models\mukeibill;
use App\Models\Crew;
use Log;

class CustomerController extends Controller
{
    public function show(){
        $crew = DB::table('crew')
        ->select('store_id', 'name', 'attendance_flag','id','birthday','delete_flag')
        ->where('company_id',Auth::user()->company_id)
        ->where('store_id',session('store_id'))
        ->where('perfectly_delete_flag','!=',1)
        ->get();
        return view('customer',[
            'crew'=>$crew
        ]);
    }

    public function search(Request $request){

        $customers = Customer::join('customer_bills', 'customers.id', '=', 'customer_bills.customer_id');

        //共通部分
        $customers = $customers->where('customers.company_id',Auth::user()->company_id)->where('customers.store_id',session('store_id'));
        //名前
        if(isset($request->name)){
            $customers = $customers->where('customers.name',"LIKE", '%'.$request->name.'%');
        }
        //カナ
        if(isset($request->kana)){
            $customers = $customers->where('customers.kana',"LIKE", '%'.$request->kana.'%');
        }
        //会社名
        if(isset($request->company)){
            $customers = $customers->where('customers.company',"LIKE", '%'.$request->company.'%');
        }
        //担当
        if(isset($request->crew_id)){
            $customers = $customers->where('customers.crew_id',$request->crew_id);
        }
        //来店日
        if(isset($request->date)){
            $customers = $customers->where('customer_bills.date',$request->date);
        }

        $customers = $customers->get(['customers.id','customers.name','customers.company','customers.crew_id','customers.bottle'])->unique();

        //従業員取得
        $crew = Crew::all()->toArray();
        //店を追加
        array_push($crew,[
            'id' => 0,
            'name' => '店'
        ]);

        return response()->json([$customers,$crew]);
    }

    public function select(Request $request){
        $customer_bills = Customer::join('customer_bills', 'customers.id', '=', 'customer_bills.customer_id');
        //id
        $customer_bills = $customer_bills->where('customers.id',$request->customer_id);
        //売上計上済みであるデータ
        $customer_bills = $customer_bills->where('customer_bills.flag',1);
        $customer_bills = $customer_bills->get(['customer_bills.id','customer_bills.crew_id','customers.name','customer_bills.date','customer_bills.cash_bill','customer_bills.credit_bill']);

        //無形データを取得
        $mukei_bills = mukeiBill::join('crew', 'mukei_bills.mukei_crew_id', '=', 'crew.id');
        //customer_billsに無形データをぶち込む
        foreach($customer_bills as $customer_bill){
            $mukei = [];
            $mukei_details = $mukei_bills->where('mukei_bills.customer_bill_id',$customer_bill->id)->get(['crew.name','mukei_bills.mukei_bill']);
            foreach($mukei_details as $mukei_detail){
                $mukei_array = array(
                    'mukei_crew_name' => $mukei_detail->name,
                    'mukei_bill' => $mukei_detail->mukei_bill,
                );
                array_push($mukei,$mukei_array);
            }
            $customer_bill->mukei = $mukei;
        }

        //従業員取得
        $crew = Crew::all()->toArray();
        //店を追加
        array_push($crew,[
            'id' => 0,
            'name' => '店'
        ]);

        return response()->json([$customer_bills,$crew]);
    }

    public function edit(Request $request){
        $customer_bill = CustomerBill::find($request->customer_bill_id);
        $mukei_bills = mukeiBill::where('customer_bill_id',$request->customer_bill_id)->get();
        $crew = Crew::all();
        //customer_billsに無形データをぶち込む
        $mukei = [];
        foreach($mukei_bills as $mukei_bill){
             $mukei_array = array(
                'mukei_crew_id' => $mukei_bill->mukei_crew_id,
                'mukei_bill' => $mukei_bill->mukei_bill,
            );
            array_push($mukei,$mukei_array);
        }
        $customer_bill->mukei = $mukei;

        return response()->json([$customer_bill,$crew]);
    }

    public function save(Request $request){
        // customer_billを更新
        //DB更新
        DB::beginTransaction();
        try {
            $bill = CustomerBill::find($request->customer_bill_id);
        $bill->fill([
            'crew_id' => $request->crew_id,
            'cash_bill' => $request->cash_bill?:null,
            'credit_bill' => $request->credit_bill?:null,
            'intax_bill' => $request->intax_bill,
            'notax_bill' => $request->notax_bill,
        ]);
        $bill->save();
        if(count($request->mukei_crew_id)>0){
            $mukei = mukeiBill::where('customer_bill_id','=',$request->customer_bill_id)->delete();
            for($i=0;$i<count($request->mukei_crew_id);$i++){
                if($request->mukei_crew_id[$i] && $request->mukei_bill[$i]){
                    mukeiBill::create([
                        'customer_bill_id' => $request->customer_bill_id,
                        'mukei_crew_id' => $request->mukei_crew_id[$i],
                        'mukei_bill' => $request->mukei_bill[$i],
                    ]);
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
        return redirect()->route('customer');
    }
}
