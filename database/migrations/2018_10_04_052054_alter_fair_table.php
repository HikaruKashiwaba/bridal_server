<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fair', function(BluePrint $table) {
            $table->string('register_zexy_id', 50)->after('zexy_flg')->nullable();
            $table->string('register_weddingpark_id', 50)->after('weddingpark_flg')->nullable();
            $table->string('register_myavi_id', 50)->after('mynavi_flg')->nullable();
            $table->string('register_gurunavi_id', 50)->after('gurunavi_flg')->nullable();
            $table->string('register_rakuten_id', 50)->after('rakuten_flg')->nullable();
            $table->string('register_minna_id', 50)->after('minna_flg')->nullable();
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
