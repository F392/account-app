<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierBill;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
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
        $selected_year = $request->year??$now_year;
        $selected_month = $request->month??Carbon::now()->format('m');
        $year_month = $now_year . '-' . $selected_month;
        //日付を生成
        $lastDate = Carbon::create($now_year, $selected_month)->endOfMonth();
        $firstDate = Carbon::create($now_year, $selected_month)->startOfMonth();
        $allDays = CarbonPeriod::create($firstDate, $lastDate)->toArray();
        //js用
        $days = [];
        foreach ($allDays as $allDay) {
            array_push($days, $allDay->format('Y-m-d'));
        }
        $lists = $this->getList($year_month);
        //業者を取得
        $suppliers = Supplier::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->select('id', 'name')->where('delete_flag', '=', 0)->get();
        //全ての業者を取得
        $AllSuppliers = Supplier::where('company_id', Auth::user()->company_id)->where('store_id', session('store_id'))->get();
        return view('supplier', [
            'year_array' => $year_array,
            'selected_year' => $selected_year,
            'month_array' => $this->month_array,
            'selected_month' => $selected_month,
            'lists' => $lists,
            'suppliers' => $suppliers,
            'allDays' => $allDays,
            'days' => $days,
            'AllSuppliers' => $AllSuppliers,
        ]);
    }

    //テーブルデータ取得
    public function getList($year_month)
    {
        $builders = DB::select("SELECT B.date
        ,GROUP_CONCAT(S.id,':',B.bill,':',B.cash_flag ORDER BY S.id) AS indvBill
        ,SUM(B.bill) AS dailyTotal
        FROM supplier_bills AS B
        INNER JOIN suppliers AS S ON B.supplier_id=S.id
        where B.date LIKE ? and S.delete_flag = 0 and S.company_id = ? and S.store_id = ?
        GROUP BY B.date", [$year_month . '%',Auth::user()->company_id,session('store_id')]);
        //indvBillデータを整形->連想配列へ
        foreach ($builders as $builder) {
            $builder->indvBill = explode(",", $builder->indvBill);
        }
        foreach ($builders as $builder) {
            for ($i = 0; $i < count($builder->indvBill); $i++) {
                $builder->indvBill[$i] = [
                    'supplier_id' => explode(":", $builder->indvBill[$i])[0],
                    'supplier_each_bill' => explode(":", $builder->indvBill[$i])[1] ?? null,
                    'cash_flag' => explode(":", $builder->indvBill[$i])[2] ?? null,
                ];
            }
        }
        
        return $builders;
    }

    public function CellSave(Request $request)
    {
        $bill = str_replace(',', '', $request->value) ?? null;
        if ($bill == 0) {
            $bill = null;
        }
        SupplierBill::updateOrCreate(
            ['supplier_id' => $request->id, 'date' => $request->date],
            ['bill' => $bill, 'cash_flag' => $request->cash_flag,'company_id'=>Auth::user()->company_id,'store_id'=>session('store_id')]
        );
    }

    public function SupplierSave(Request $request)
    {
        foreach ($request->supplier_name as $supplier_name) {
            if(isset($supplier_name)){
                Supplier::create([
                    'company_id' => Auth::user()->company_id,
                    'store_id' => session('store_id'),
                    'delete_flag' => 0,
                    'name' => $supplier_name,
                ]);
            }
        }
        return redirect()->route('supplier');
    }

    public function edit(Request $request){
        $supplier = Supplier::find($request->supplier_id);
        $supplier->fill([
            'name' => $request->supplier_name,
            'delete_flag' => $request->delete_flag,
        ]);
        $supplier->save();
        
        if($request->year && $request->month){
            return redirect(route('supplier_search', [
                'year' => $request->year,
                'month' => $request->month
            ]));
        }else{
            return redirect()->route('supplier');
        }
    }
}
