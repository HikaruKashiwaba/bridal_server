<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairContentPartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_content_part', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('fair_content_id')->notnull();
          $table->integer('part')->notnull();
          $table->integer('valid_flg')->notnull()->default('1');
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
        Schema::dropIfExists('fair_content_part');
    }
}
