<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    //ログインフォーム表示
    Route::get('/', 'App\Http\Controllers\AuthController@showLogin')->name('showLogin');
//ログイン処理
    Route::post('login', 'App\Http\Controllers\AuthController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {

//ホーム画面を表示する
    Route::get('home', 'App\Http\Controllers\HomeController@show')->name('home');

//売上計上
    Route::get('bill', 'App\Http\Controllers\BillController@showList')->name('bill');
    Route::post('bill/save', 'App\Http\Controllers\BillController@save')->name('bill_save');
    Route::get('bill/search', 'App\Http\Controllers\BillController@search')->name('bill_search');
    Route::get('bill/add', 'App\Http\Controllers\BillController@add')->name('bill_add');
    Route::post('bill/save_new_bill', 'App\Http\Controllers\BillController@SaveNewBill')->name('bill_save_new_bill');

//提携業社
    Route::get('supplier', 'App\Http\Controllers\SupplierController@show')->name('supplier');
    Route::post('supplier/cell_save', 'App\Http\Controllers\SupplierController@CellSave')->name('supplier_cell_save');
    Route::post('supplier/supplier_save', 'App\Http\Controllers\SupplierController@SupplierSave')->name('supplier_save');
    Route::get('supplier/list', 'App\Http\Controllers\SupplierController@list')->name('supplier_list');
    Route::post('supplier/edit', 'App\Http\Controllers\SupplierController@edit')->name('supplier_edit');

//従業員別売上
    Route::get('staff', 'App\Http\Controllers\StaffController@show')->name('staff');
    Route::get('staff/search', 'App\Http\Controllers\StaffController@search')->name('staff_search');
    Route::get('staff/daily_bill', 'App\Http\Controllers\StaffController@DailyBill')->name('staff_daily_bill');

//月次売上
    Route::get('month', 'App\Http\Controllers\MonthController@show')->name('month');
    Route::get('month/search', 'App\Http\Controllers\MonthController@search')->name('month_search');

//顧客別集計
    Route::get('customer', 'App\Http\Controllers\CustomerController@show')->name('customer');
    Route::get('customer/search', 'App\Http\Controllers\CustomerController@search')->name('customer_search');

//売掛
    Route::get('kake', 'App\Http\Controllers\KakeController@show')->name('kake');
    Route::post('kake/save', 'App\Http\Controllers\KakeController@save')->name('kake_save');

//日払い
    Route::get('DailyPayment', 'App\Http\Controllers\DailyPaymentController@show')->name('DailyPayment');
    Route::get('DailyPayment/add', 'App\Http\Controllers\DailyPaymentController@add')->name('DailyPayment_add');
    Route::get('DailyPayment/detail', 'App\Http\Controllers\DailyPaymentController@detail')->name('DailyPayment_detail');
    Route::post('DailyPayment/delete', 'App\Http\Controllers\DailyPaymentController@delete')->name('DailyPayment_delete');
    Route::get('DailyPayment/EditList', 'App\Http\Controllers\DailyPaymentController@EditList')->name('DailyPayment_EditList');
    Route::post('DailyPayment/edit', 'App\Http\Controllers\DailyPaymentController@edit')->name('DailyPayment_edit');

//レジ金
    Route::get('cashier', 'App\Http\Controllers\CashierController@show')->name('cashier');
    Route::get('cashier/detail', 'App\Http\Controllers\CashierController@detail')->name('cashier_detail');
    Route::post('cashier/save', 'App\Http\Controllers\CashierController@save')->name('cashier_save');

    //店舗変更
    Route::get('store', 'App\Http\Controllers\StoreController@change')->name('store');

    //ログアウト
    Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
});
