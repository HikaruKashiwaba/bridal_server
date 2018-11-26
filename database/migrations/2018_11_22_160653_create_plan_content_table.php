<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_content', function (Blueprint $table) {
            $table->increments('plan_id');
            $table->integer('order_id');
            $table->integer('check_box');
            $table->string('privilege_text');
            $table->char('weddingpark_flg', 1);
            $table->char('mynavi_flg', 1);
            $table->char('gurunavi_flg', 1);
            $table->char('zexy_flg', 1);
            $table->char('minna_flg', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_content');
    }
}

