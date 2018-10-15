<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fair_content', function(BluePrint $table) {
          $table->integer('start_hour1')->change();
          $table->integer('start_minute1')->change();
          $table->integer('end_hour1')->change();
          $table->integer('end_minute1')->change();
          $table->integer('start_hour2')->change();
          $table->integer('start_minute2')->change();
          $table->integer('end_hour2')->change();
          $table->integer('end_minute2')->change();
            $table->integer('start_hour3')->change();
            $table->integer('start_minute3')->change();
            $table->integer('end_hour3')->change();
            $table->integer('end_minute3')->change();
            $table->integer('start_hour4')->change();
            $table->integer('start_minute4')->change();
            $table->integer('end_hour4')->change();
            $table->integer('end_minute4')->change();
            $table->integer('start_hour5')->change();
            $table->integer('start_minute5')->change();
            $table->integer('end_hour5')->change();
            $table->integer('end_minute5')->change();
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
