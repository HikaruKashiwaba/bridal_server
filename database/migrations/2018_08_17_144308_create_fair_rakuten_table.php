<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairRakutenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_rakuten', function (Blueprint $table) {
            $table->unsignedInteger('fair_id')->notnull();
            $table->string('description', 200);
            $table->char('net_reserve_period_day', 1);
            $table->char('net_reserve_period_time', 1);
            $table->char('phone_reserve_flg', 1);
            $table->string('start_hour1', 2);
            $table->string('start_minute1', 2);
            $table->string('end_hour1', 2);
            $table->string('end_minute1', 2);
            $table->string('start_hour2', 2);
            $table->string('start_minute2', 2);
            $table->string('end_hour2', 2);
            $table->string('end_minute2', 2);
            $table->string('start_hour3', 2);
            $table->string('start_minute3', 2);
            $table->string('end_hour3', 2);
            $table->string('end_minute3', 2);
            $table->string('start_hour4', 2);
            $table->string('start_minute4', 2);
            $table->string('end_hour4', 2);
            $table->string('end_minute4', 2);
            $table->string('start_hour5', 2);
            $table->string('start_minute5', 2);
            $table->string('end_hour5', 2);
            $table->string('end_minute5', 2);
            $table->timestamps();
            $table->primary('fair_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fair_rakuten');
    }
}
