<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerOptionsRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_options_relation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id', false, true);
            $table->integer('partner_option_id', false, true);
            $table->timestamps();

            $table->unique(['partner_id', 'partner_option_id']);
            $table->foreign('partner_id')->on('partners')->references('id');
            $table->foreign('partner_option_id')->on('partner_options')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_options_relation');
    }
}
