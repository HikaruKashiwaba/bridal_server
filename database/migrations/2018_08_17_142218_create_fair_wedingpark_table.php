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
            $table->string('description', 120)->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->string('price_per_person', 4)->nullable();
            $table->string('price_remarks', 100)->nullable();
            $table->string('pc_url', 100)->nullable();
            $table->char('pc_insert_url_flg', 1)->nullable();
            $table->char('pc_ga_flg', 1)->nullable();
            $table->string('phone_url', 100)->nullable();
            $table->char('phone_insert_url_flg', 1)->nullable();
            $table->char('phone_ga_flg', 1)->nullable();
            $table->string('required_hour', 2)->nullable();
            $table->string('required_minute', 2)->nullable();
            $table->unsignedInteger('benefit')->nullable();
            $table->char('reflect_status', 1)->notnull();
            $table->timestamps();
            $table->primary('fair_id');

            $table->softDeletes();
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
