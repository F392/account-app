<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->comment('お客様ID');
            $table->unsignedBigInteger('crew_id')->comment('従業員ID(支払い担当の従業員ID)');
            $table->date('date')->comment('来店日');
            $table->integer('people_number')->comment('来店人数');
            $table->unsignedBigInteger('cash_bill')->nullable()->comment('現金');
            $table->unsignedBigInteger('credit_bill')->nullable()->comment('カード');
            $table->unsignedBigInteger('kake_bill')->nullable()->comment('売掛');
            $table->unsignedBigInteger('intax_bill')->nullable()->comment('売上(税込)');
            $table->unsignedBigInteger('notax_bill')->nullable()->comment('売上(税抜)');
            $table->bigInteger('flag')->nullable()->comment('お会計フラグ(null:未処理、1:売掛なし、2:売掛あり)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_bills');
    }
};
