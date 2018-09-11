<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFairMynaviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fair_mynavi', function (Blueprint $table) {
            $table->integer('master_id')->after('fair_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fair_mynavi', function (Blueprint $table) {
            $table->dropColumn('master_id');
        });
    }
}
