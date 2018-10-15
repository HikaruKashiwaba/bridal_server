<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fair', function(BluePrint $table) {
            $table->dropColumn('register_weddingpark_id');
            $table->dropColumn('register_myavi_id');
            $table->dropColumn('register_gurunavi_id');
            $table->dropColumn('register_rakuten_id');
            $table->dropColumn('register_zexy_id');
            $table->dropColumn('register_minna_id');
            $table->integer('end_minute5')->after('end_minute')->nullable();
            $table->integer('end_hour5')->after('end_minute')->nullable();
            $table->integer('start_minute5')->after('end_minute')->nullable();
            $table->integer('start_hour5')->after('end_minute')->nullable();
            $table->integer('part5')->after('end_minute')->nullable();
            $table->integer('end_minute4')->after('end_minute')->nullable();
            $table->integer('end_hour4')->after('end_minute')->nullable();
            $table->integer('start_minute4')->after('end_minute')->nullable();
            $table->integer('start_hour4')->after('end_minute')->nullable();
            $table->integer('part4')->after('end_minute')->nullable();
            $table->integer('end_minute3')->after('end_minute')->nullable();
            $table->integer('end_hour3')->after('end_minute')->nullable();
            $table->integer('start_minute3')->after('end_minute')->nullable();
            $table->integer('start_hour3')->after('end_minute')->nullable();
            $table->integer('part3')->after('end_minute')->nullable();
            $table->integer('end_minute2')->after('end_minute')->nullable();
            $table->integer('end_hour2')->after('end_minute')->nullable();
            $table->integer('start_minute2')->after('end_minute')->nullable();
            $table->integer('start_hour2')->after('end_minute')->nullable();
            $table->integer('part2')->after('end_minute')->nullable();
            $table->integer('end_minute1')->after('end_minute')->nullable();
            $table->integer('end_hour1')->after('end_minute')->nullable();
            $table->integer('start_minute1')->after('end_minute')->nullable();
            $table->integer('start_hour1')->after('end_minute')->nullable();
            $table->integer('part1')->after('end_minute')->nullable();
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
