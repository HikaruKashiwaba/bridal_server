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
            $table->char('reserve_way', 1);
            $table->char('benefit_flg', 1);
            $table->char('specify_time_flg', 1);
            $table->string('display_end_day', 2);
            $table->char('limited_gurunavi_flg', 1);
            $table->char('one_person_flg', 1);
            $table->string('catch_copy', 30);
            $table->string('description', 250);
            $table->char('capacity', 1);
            $table->string('image_description', 30);
            $table->string('attention_point', 30);
            $table->char('price_status', 1);
            $table->unsignedInteger('price');
            $table->char('tax_included', 1);
            $table->char('tax_calculation', 1);
            $table->char('counsel_type', 1);
            $table->char('reserve_button_flg', 1);
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
        Schema::dropIfExists('fair_gurunavi');
    }
}
