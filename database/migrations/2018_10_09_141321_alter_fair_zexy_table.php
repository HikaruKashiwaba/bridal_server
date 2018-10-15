<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairZexyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fair_zexy', function(BluePrint $table) {
            $table->dropColumn('part1');
            $table->dropColumn('start_hour1');
            $table->dropColumn('start_minute1');
            $table->dropColumn('end_hour1');
            $table->dropColumn('end_minute1');
            $table->dropColumn('part2');
            $table->dropColumn('start_hour2');
            $table->dropColumn('start_minute2');
            $table->dropColumn('end_hour2');
            $table->dropColumn('end_minute2');
            $table->dropColumn('part3');
            $table->dropColumn('start_hour3');
            $table->dropColumn('start_minute3');
            $table->dropColumn('end_hour3');
            $table->dropColumn('end_minute3');
            $table->dropColumn('part4');
            $table->dropColumn('start_hour4');
            $table->dropColumn('start_minute4');
            $table->dropColumn('end_hour4');
            $table->dropColumn('end_minute4');
            $table->dropColumn('part5');
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
