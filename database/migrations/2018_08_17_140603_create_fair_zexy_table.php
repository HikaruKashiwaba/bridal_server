<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairZexyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_zexy', function (Blueprint $table) {
            $table->unsignedInteger('fair_id')->notnull();
            $table->char('fair_type', 1)->notnull();
            $table->char('realtime_reserve_flg', 1);
            $table->string('required_time', 3);
            $table->string('description', 100)->notnull();
            $table->char('﻿multi_part_flg', 1);
            $table->string('place', 200);
            $table->string('parking', 50);
            $table->string('target', 100);
            $table->string('content_other', 500);
            $table->string('tel_number1', 15)->notnull();
            $table->char('tel_type1', 1)->notnull();
            $table->string('tel_staff1', 100);
            $table->string('tel_number2', 15);
            $table->char('tel_type2', 1);
            $table->string('tel_staff2', 100);
            $table->string('benefit', 50);
            $table->string('benefit_period', 50);
            $table->string('benefit_remarks', 50);
            $table->string('catch_copy', 25);
            $table->string('attention_point', 100);
            $table->string('attention_point_staff', 10);
            $table->string('attention_point_staff_job', 15);
            $table->string('question', 200);
            $table->char('required_question_flg', 1);
            $table->string('reception_time', 50);
            $table->string('reception_staff', 50);
            $table->char('reserve_way', 1);
            $table->string('net_reserve_day', 2);
            $table->char('net_reserve_time', 1);
            $table->string('phone_reserve_day1', 2);
            $table->string('phone_reserve_day2', 2);
            $table->string('post_start_day', 10);
            $table->string('post_end_day', 10)->notnull();
            $table->string('part1', 2);
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
            $table->char('del_flg', 1)->notnull();
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
        Schema::dropIfExists('fair_zexy');
    }
}
