<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id', false, true);
            $table->integer('partner_id', false, true)->nullable();

            $table->tinyInteger('status');
            $table->string('status_description', '1000')->nullable();

            $table->float('sum')->nullable();
            $table->integer('offer_id', false, true)->nullable();
            $table->float('cashback_sum')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('partner_id')->on('partners')->references('id');
            $table->foreign('offer_id')->on('offers')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checks');
    }
}
