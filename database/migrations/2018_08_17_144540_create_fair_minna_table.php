<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairMinnaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_minna', function (Blueprint $table) {
            $table->unsignedInteger('fair_id')->notnull();
            $table->char('disp_sub_flg', 1);
            $table->string('description', 300);
            $table->string('benefit', 50);
            $table->char('reserve_flg', 1)->notnull();
            $table->char('price_flg', 1)->notnull();
            $table->string('reservation_description', 20);
            $table->string('price_description', 20);
            $table->string('post_year', 4);
            $table->string('post_month', 2);
            $table->string('post_day', 2);
            $table->string('post_time', 2);
            $table->char('reservable_period', 1);
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
        Schema::dropIfExists('fair_minna');
    }
}
