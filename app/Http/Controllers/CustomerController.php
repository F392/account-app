<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
}
