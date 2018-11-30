<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->increments('plan_id');
            $table->integer('member_id');
            $table->string('plan_name');
            $table->string('plan_title');
            $table->string('plan_detail');
            $table->integer('price');
            $table->string('remarks');
            $table->integer('number_people');
            $table->integer('plus_one');
            $table->integer('minus_one');
            $table->timestamp('published_start');
            $table->timestamp('published_end');
            $table->string('style');
            $table->char('weddingpark_flg', 1);
            $table->char('mynavi_flg', 1);
            $table->char('gurunavi_flg', 1);
            $table->char('zexy_flg', 1);
            $table->char('minna_flg', 1);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan');
    }
}
