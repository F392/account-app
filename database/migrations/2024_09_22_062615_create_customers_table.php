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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->string('name', 100)->comment('お客様氏名');
            $table->string('kana', 100)->nullable()->comment('指名カナ');
            $table->string('company')->nullable()->comment('会社名');
            $table->unsignedBigInteger('crew_id')->comment('担当従業員ID');
            $table->string('birthday', 5)->nullable()->comment('誕生日');
            $table->string('bottle')->nullable()->comment('ボトル残量');
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
