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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string("bvn")->nullable();
            $table->string("nin")->nullable();
            $table->string("tx_ref")->nullable();
            $table->string("order_ref")->nullable();
            $table->string("account_ref")->nullable();
            $table->string("virtual_card_id")->nullable();
            $table->string("account_balance")->nullable();
            $table->string("account_name")->nullable();
            $table->string("account_number")->nullable();
            $table->string("bank_code")->nullable();
            $table->string("bank_name")->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders');
    }
};
