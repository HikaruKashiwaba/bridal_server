<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairEventDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_event_date', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->notnull();
            $table->integer('fair_id')->notnull();
            $table->char('site_type', 1)->nullable();
            $table->string('date', 8)->notnull();
            $table->char('representative_flg', 1)->default('0')->notnull();
            $table->char('reflect_flg', 1)->default('0')->notnull();
            $table->char('lock_flg', 1)->default('0')->notnull();
            $table->char('stop_flg', 1)->default('0')->notnull();
            $table->string('register_zexy_id', 50)->nullable();
            $table->string('register_wepa_id', 50)->nullable();
            $table->string('register_myavi_id', 50)->nullable();
            $table->string('register_gurunavi_id', 50)->nullable();
            $table->string('register_rakuten_id', 50)->nullable();
            $table->string('register_minna_id', 50)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('fair_event_date');
    }
}
