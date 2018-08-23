<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Account extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->increments('id')->notnull();
            $table->unsignedInteger('member_id')->notnull();
            $table->char('site_type', 1)->notnull();
            $table->string('login_id', 32)->notnull();
            $table->string('merchant_id', 32)->nullable();
            $table->string('password', 32)->notnull();
            $table->timestamps();

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
        Schema::dropIfExists('account');
    }
}
