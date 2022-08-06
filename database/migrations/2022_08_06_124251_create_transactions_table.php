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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->integer('sell_amount');
            $table->string('sell_currency');
            $table->integer('receiving_amount');
            $table->string('receiving_currency');
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();

            $table->foreign('seller_id')
                ->references('id')
                ->on('users');

            $table->foreign('buyer_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
