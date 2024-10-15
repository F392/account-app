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
        Schema::create('crew', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->string('name', 50)->comment('従業員名');
            $table->unsignedBigInteger('table_id')->nullable()->comment('どこのテーブルにいるか');
            $table->unsignedBigInteger('attendance_flag')->comment('0:休み、1:出勤');
            $table->string('birthday', 5)->nullable()->comment('誕生日');
            $table->bigInteger('delete_flag')->comment('0:現存、1:削除済み');
            $table->integer('perfectly_delete_flag')->comment('1:完全に削除');
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
        Schema::dropIfExists('crew');
    }
};
