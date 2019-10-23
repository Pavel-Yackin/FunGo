<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 255)->charset('utf8')->collation('utf8_unicode_ci');
            $table->integer('partner_type_id', false, true)->nullable();

            $table->string('address', 1000)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('mail', 255)->nullable();
            $table->string('schedule', 511)->nullable();
            $table->text('description')->nullable();
            $table->text('cashback_description')->nullable();

            $table->string('logo', 255)->nullable();

            $table->index('name');
            $table->foreign('partner_type_id')->references('id')->on('partner_types');

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
        Schema::table('partners', function (Blueprint $table) {
            $table->dropForeign('partners_partner_type_id_foreign');
        });
        Schema::dropIfExists('partners');
    }
}
