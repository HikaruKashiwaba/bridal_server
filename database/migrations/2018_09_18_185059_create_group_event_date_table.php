<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupEventDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_event_date', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->notnull();
            $table->integer('group_id')->notnull();
            $table->string('date', 8)->notnull();
            $table->char('reflect_flg', 1)->default('0')->notnull();
            $table->char('lock_flg', 1)->default('0')->notnull();
            $table->char('stop_flg', 1)->default('0')->notnull();
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
        Schema::dropIfExists('group_event_date');
    }
}
