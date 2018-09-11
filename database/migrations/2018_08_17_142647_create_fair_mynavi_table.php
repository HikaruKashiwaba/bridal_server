<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairMynaviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_mynavi', function (Blueprint $table) {
            $table->integer('fair_id')->notnull();
            $table->string('description', 500);
            $table->char('reserve_way', 1);
            $table->char('﻿multi_part_check', 1);
            $table->string('place', 200);
            $table->string('place_remarks', 500);
            $table->string('place_other', 100);
            $table->string('net_reserve_period_day', 2);
            $table->string('net_reserve_period_time', 2);
            $table->string('phone_reserve_period_day', 2);
            $table->string('phone_reserve_period_time', 2);
            $table->string('target', 100);
            $table->string('content_other', 500);
            $table->char('benefit_flg', 1);
            $table->char('limited_benefit_flg', 1);
            $table->string('benefit', 500);
            $table->char('required_hour', 2);
            $table->char('required_minute', 2);
            $table->char('prevent_selection_flg', 1);
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
            $table->char('reflect_status', 1)->notnull();
            $table->softDeletes();
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
        Schema::dropIfExists('fair_mynavi');
    }
}
