<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_date', function (Blueprint $table) {
            $table->increments('id')->notnull();
            $table->integer('fair_id')->notnull();
            $table->integer('register_id')->nullable();
            $table->char('site_type', 1)->nullable();
            $table->string('date', 30)->notnull();
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
        Schema::dropIfExists('event_date');
    }
}
