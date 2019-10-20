<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_id', false, true);

            $table->tinyInteger('type');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('finish_date')->nullable();

            $table->text('description')->nullable();
            $table->float('value')->nullable();
            $table->timestamps();

            $table->foreign('partner_id')->on('partners')->references('id');
            $table->index('type');
            $table->index('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_partner_id_foreign');
        });

        Schema::dropIfExists('offers');
    }
}
