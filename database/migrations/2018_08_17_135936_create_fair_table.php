<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->notnull();
            $table->string('title', 100);
            $table->unsignedInteger('image_id');
            $table->string('start_hour', 2)->notnull();
            $table->string('start_minute', 2)->notnull();
            $table->string('end_hour', 2)->notnull();
            $table->string('end_minute', 2)->notnull();
            $table->char('weddingpark_flg', 1);
            $table->char('mynavi_flg', 1);
            $table->char('gurunavi_flg', 1);
            $table->char('rakuten_flg', 1);
            $table->char('zexy_flg', 1);
            $table->char('minna_flg', 1);
            $table->char('reflect_status', 1)->notnull();
            $table->char('del_flg', 1)->notnull();
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
        Schema::dropIfExists('fair');
    }
}
