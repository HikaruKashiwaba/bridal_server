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
            $table->integer('fair_id')->notnull();
            $table->char('fair_type', 1)->notnull();
            $table->char('realtime_reserve_flg', 1)->nullable();
            $table->string('required_time', 3)->nullable();
            $table->string('description', 100)->notnull();
            $table->string('place', 200)->nullable();
            $table->string('parking', 50)->nullable();
            $table->string('target', 100)->nullable();
            $table->string('content_other', 500)->nullable();
            $table->string('tel_number1', 15)->notnull();
            $table->char('tel_type1', 1)->notnull();
            $table->string('tel_staff1', 100)->nullable();
            $table->string('tel_number2', 15)->nullable();
            $table->char('tel_type2', 1)->nullable();
            $table->string('tel_staff2', 100)->nullable();
            $table->string('benefit', 50)->nullable();
            $table->string('benefit_period', 50)->nullable();
            $table->string('benefit_remarks', 50)->nullable();
            $table->string('catch_copy', 25)->nullable();
            $table->string('attention_point', 100)->nullable();
            $table->string('attention_point_staff', 10)->nullable();
            $table->string('attention_point_staff_job', 15)->nullable();
            $table->string('question', 200)->nullable();
            $table->char('required_question_flg', 1)->nullable();
            $table->string('reception_time', 50)->nullable();
            $table->string('reception_staff', 50)->nullable();
            $table->char('reserve_way', 1)->nullable();
            $table->string('net_reserve_day', 2)->nullable();
            $table->char('net_reserve_time', 1)->nullable();
            $table->string('phone_reserve_day1', 2)->nullable();
            $table->string('phone_reserve_day2', 2)->nullable();
            $table->string('post_start_day', 10)->nullable();
            $table->string('post_end_day', 10)->notnull();
            $table->string('part1', 2)->nullable();
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
        Schema::dropIfExists('fair_zexy');
    }
}
