<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFairZexyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fair_zexy', function (Blueprint $table) {
            $table->string('request_change_count', 5)->after('reserve_way')->nullable();
            $table->char('request_change_config', 1)->after('reserve_way')->nullable();
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
            $table->dropColumn('request_change_config');
            $table->dropColumn('request_change_count');
        });
    }
}
