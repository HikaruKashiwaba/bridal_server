<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairGurunaviScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_gurunavi_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fair_id')->notnull();
            $table->integer('part')->notnull();
            $table->string('master_id', 100)->nullable();
            $table->integer('start_hour1')->nullable();
            $table->integer('start_minute1')->nullable();
            $table->string('content_text1', 40)->nullable();
            $table->integer('start_hour2')->nullable();
            $table->integer('start_minute2')->nullable();
            $table->string('content_text2', 40)->nullable();
            $table->integer('start_hour3')->nullable();
            $table->integer('start_minute3')->nullable();
            $table->string('content_text3', 40)->nullable();
            $table->integer('start_hour4')->nullable();
            $table->integer('start_minute4')->nullable();
            $table->string('content_text4', 40)->nullable();
            $table->integer('start_hour5')->nullable();
            $table->integer('start_minute5')->nullable();
            $table->string('content_text5', 40)->nullable();
            $table->char('reflect_status', 1)->notnull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fair_gurunavi_schedule');
    }
}
