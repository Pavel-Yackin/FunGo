<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChecksTableAddNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->string('number')
                ->charset('utf8')
                ->collation('utf8_unicode_ci')
                ->after('id')
                ->nullable();

            $table->unique(['number'], 'partners_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropUnique('partners_number_unique');
            $table->dropColumn('number');
        });
    }
}
