<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairGurunaviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_gurunavi', function (Blueprint $table) {
            $table->unsignedInteger('fair_id')->notnull();
            $table->char('reserve_way', 1)->nullable();
            $table->char('benefit_flg', 1)->nullable();
            $table->char('specify_time_flg', 1)->nullable();
            $table->string('display_end_day', 2)->nullable();
            $table->char('limited_gurunavi_flg', 1)->nullable();
            $table->char('one_person_flg', 1)->nullable();
            $table->string('catch_copy', 30)->nullable();
            $table->string('description', 250)->nullable();
            $table->char('capacity', 1)->nullable();
            $table->string('image_description', 30)->nullable();
            $table->string('attention_point', 30)->nullable();
            $table->char('price_status', 1)->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->char('tax_included', 1)->nullable();
            $table->char('tax_calculation', 1)->nullable();
            $table->char('counsel_type', 1)->nullable();
            $table->char('reserve_button_flg', 1)->nullable();
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
        Schema::dropIfExists('fair_gurunavi');
    }
}
