<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crew;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerBill;
use Illuminate\Support\Facades\Auth;

class KakeController extends Controller
{
    public $month_array = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

    public function show()
    {
        //掛けのあるデータを取得(flag=2)
        $kakes = DB::select('SELECT 
        customer_bills.id,
        name,
        company,
        customer_bills.crew_id,
        date,
        kake_bill
        FROM customers
        JOIN customer_bills 
        ON customers.id = customer_bills.customer_id 
        WHERE customer_bills.flag = 2 and customers.company_id = ? and customers.store_id = ?
        ORDER BY date',[Auth::user()->company_id,session('store_id')]);

        //従業員データ取得
        $crew = Crew::all()->toArray();

        return view('kake',[
            'kakes' => $kakes,
            'crew' => $crew
        ]);
    }

    public function save(Request $request){
        $customer_bill = CustomerBill::find($request->customer_bill_id);
        $customer_bill->fill([
            'flag' => 1,
        ]);
        $customer_bill->save();
        return redirect()->route('kake');
    }
}
