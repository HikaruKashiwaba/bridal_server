<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFairZexyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fair_zexy', function (Blueprint $table) {
            $table->integer('master_id')->after('fair_id')->nullable();
            $table->string('short_title', 20)->default('')->after('required_time');
            $table->string('change_start_day', 10)->after('post_end_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fair_zexy', function (Blueprint $table) {
            $table->dropColumn('master_id');
            $table->dropColumn('short_title');
            $table->dropColumn('change_start_day');
        });
    }
}
