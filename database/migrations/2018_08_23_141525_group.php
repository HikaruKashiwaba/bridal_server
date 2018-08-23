<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Group extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::cteate('group', function(Blueprint $table) {
            $table->unsignedInteger('id')->nutnull();
            $table->unsignedInteger('member_id')->notnull();
            $table->string('group_name', 32)->notnull();
            $table->timestamps();

            $table->unique(['id', 'member_id']);

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
        Schema::dropIfExists('group');
    }
}
