<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairContentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_content_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fair_content_id')->notnull();
            $table->integer('fair_content_part_id')->notnull();
            $table->integer('order_no')->notnull();
            $table->integer('start_hour')->nullable();
            $table->integer('start_minute')->nullable();
            $table->integer('end_hour')->nullable();
            $table->integer('end_minute')->nullable();
            $table->string('title', 100)->notnull();
            $table->integer('zebra_reserve_count')->notnull();
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
        Schema::dropIfExists('fair_content_detail');
    }
}
