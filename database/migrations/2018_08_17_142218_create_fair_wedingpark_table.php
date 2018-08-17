<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairWedingparkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_wedingpark', function (Blueprint $table) {
            $table->unsignedInteger('fair_id')->notnull();
            $table->string('description', 120);
            $table->unsignedInteger('price');
            $table->string('price_per_person', 4);
            $table->string('price_remarks', 100);
            $table->string('pc_url', 100);
            $table->char('pc_insert_url_flg', 1);
            $table->char('pc_ga_flg', 1);
            $table->string('phone_url', 100);
            $table->char('phone_insert_url_flg', 1);
            $table->char('phone_ga_flg', 1);
            $table->string('required_hour', 2);
            $table->string('required_minute', 2);
            $table->unsignedInteger('benefit');
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
        Schema::dropIfExists('fair_wedingpark');
    }
}
