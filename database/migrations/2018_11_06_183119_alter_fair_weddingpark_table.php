<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairWeddingparkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
        Schema::table('fair_weddingpark', function(BluePrint $table) {
            $table->string('benefit', 100)->change();
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
