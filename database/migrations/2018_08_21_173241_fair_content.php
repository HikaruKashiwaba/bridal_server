<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FairContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_content', function(BluePrint $table) {
            $table->increments('id')->notnull();
            $table->integer('fair_id')->notnull();
            $table->char('site_type', 1)->notnull();
            $table->integer('order_id')->notnull();
            $table->char('content', 1)->nullable();
            $table->string('other_title', 100)->nullable();
            $table->char('reserve_status', 1)->nullable();
            $table->char('reserve_count', 1)->nullable();
            $table->char('reserve_unit', 1)->nullable();
            $table->char('price_status', 1)->nullable();
            $table->integer('price')->nullable();
            $table->string('price_per_person', 3)->nullable();
            $table->string('required_time', 3)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('content_detail', 500)->nullable();
            $table->string('event_kbn1', 35)->nullable();
            $table->string('event_kbn2', 35)->nullable();
            $table->integer('image_id')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fair_content');
    }
}
