<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_zexy_id', 100)->nullable();
            $table->string('image_wedingpark_id', 100)->nullable();
            $table->string('image_mynavi_id', 100)->nullable();
            $table->string('image_gurunavi_id', 100)->nullable();
            $table->string('image_rakuten_id', 100)->nullable();
            $table->string('image_minna_id', 100)->nullable();
            $table->string('file_name', 100)->nullable();
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
        Schema::dropIfExists('image');
    }
}
