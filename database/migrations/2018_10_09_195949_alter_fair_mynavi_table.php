<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairMynaviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fair_mynavi', function(BluePrint $table) {
            $table->dropColumn('start_hour1');
            $table->dropColumn('start_minute1');
            $table->dropColumn('end_hour1');
            $table->dropColumn('end_minute1');
            $table->dropColumn('start_hour2');
            $table->dropColumn('start_minute2');
            $table->dropColumn('end_hour2');
            $table->dropColumn('end_minute2');
            $table->dropColumn('start_hour3');
            $table->dropColumn('start_minute3');
            $table->dropColumn('end_hour3');
            $table->dropColumn('end_minute3');
            $table->dropColumn('start_hour4');
            $table->dropColumn('start_minute4');
            $table->dropColumn('end_hour4');
            $table->dropColumn('end_minute4');
            $table->dropColumn('start_hour5');
            $table->dropColumn('start_minute5');
            $table->dropColumn('end_hour5');
            $table->dropColumn('end_minute5');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
