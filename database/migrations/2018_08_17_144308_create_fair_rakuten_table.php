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
            $table->string('description', 200)->nullable();
            $table->char('net_reserve_period_day', 1)->nullable();
            $table->char('net_reserve_period_time', 1)->nullable();
            $table->char('phone_reserve_flg', 1)->nullable();
            $table-> char('part_count', 1)->nullable();
            $table->string('start_hour1', 2)->nullable();
            $table->string('start_minute1', 2)->nullable();
            $table->string('end_hour1', 2)->nullable();
            $table->string('end_minute1', 2)->nullable();
            $table->string('start_hour2', 2)->nullable();
            $table->string('start_minute2', 2)->nullable();
            $table->string('end_hour2', 2)->nullable();
            $table->string('end_minute2', 2)->nullable();
            $table->string('start_hour3', 2)->nullable();
            $table->string('start_minute3', 2)->nullable();
            $table->string('end_hour3', 2)->nullable();
            $table->string('end_minute3', 2)->nullable();
            $table->string('start_hour4', 2)->nullable();
            $table->string('start_minute4', 2)->nullable();
            $table->string('end_hour4', 2)->nullable();
            $table->string('end_minute4', 2)->nullable();
            $table->string('start_hour5', 2)->nullable();
            $table->string('start_minute5', 2)->nullable();
            $table->string('end_hour5', 2)->nullable();
            $table->string('end_minute5', 2)->nullable();
            $table->timestamps();
            $table->primary('fair_id');

            $table->softDeletes();
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
