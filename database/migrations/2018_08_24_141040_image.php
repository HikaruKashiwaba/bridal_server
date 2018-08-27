<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Image extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image', function(Blueprint $table) {
            $table->unsignedInteger('member_id')->notnull();
            $table->unsignedInteger('file_id')->notnull();
            $table->string('file_name', 32)->notnull();
            $table->timestamps();

            $table->unique(['member_id', 'file_id']);

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
        Schema::dropIfExists('image');
    }
}
