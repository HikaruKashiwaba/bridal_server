<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairZexyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fair_zexy', function(BluePrint $table) {
            $table->string('reserve_way', 2)->change();
            $table->string('part2', 2)->after('end_minute1')->nullable();
            $table->string('part3', 2)->after('end_minute2')->nullable();
            $table->string('part4', 2)->after('end_minute3')->nullable();
            $table->string('part5', 2)->after('end_minute4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
