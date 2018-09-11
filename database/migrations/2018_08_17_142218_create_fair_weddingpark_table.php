<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairWeddingparkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_weddingpark', function (Blueprint $table) {
            $table->integer('fair_id')->notnull();
            $table->string('description', 120)->nullable();
            $table->integer('price')->nullable();
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
            $table->integer('benefit')->nullable();
            $table->char('reflect_status', 1)->notnull();
            $table->softDeletes();
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
        Schema::dropIfExists('fair_weddingpark');
    }
}
