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
            $table->integer('fair_id')->notnull();
            $table->char('disp_sub_flg', 1)->nullable();
            $table->string('event_kbn', 20)->nullable();
            $table->string('description', 300)->nullable();
            $table->string('benefit', 50)->nullable();
            $table->char('reserve_flg', 1)->notnull();
            $table->char('price_flg', 1)->notnull();
            $table->string('reservation_description', 20)->nullable();
            $table->string('price_description', 20)->nullable();
            $table->string('post_year', 4)->nullable();
            $table->string('post_month', 2)->nullable();
            $table->string('post_day', 2)->nullable();
            $table->string('post_time', 2)->nullable();
            $table->char('reservable_period', 1)->nullable();
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
        Schema::dropIfExists('fair_minna');
    }
}
