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
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->comment('会社名');
            $table->string('image')->nullable()->comment('会社ロゴ画像');
            $table->string('zipcode', 10)->nullable()->comment('郵便番号');
            $table->string('address', 100)->nullable()->comment('住所');
            $table->string('phone_number', 18)->nullable()->comment('電話番号');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('responder')->nullable()->comment('担当者名');
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
        Schema::dropIfExists('companies');
    }
};
