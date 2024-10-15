<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * 店舗変更
     * @param  \Illuminate\Http\Request  $request
     */
    public function change(Request $request){
        $request->session()->put('store_id', $request->store_id);
        return redirect()->route('home');
    }
}
