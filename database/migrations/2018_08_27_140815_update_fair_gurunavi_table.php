<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFairGurunaviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fair_gurunavi', function (Blueprint $table) {
            // $table->string('capacity', 3)->nullable()->change();
            $table->string('event_kbn', 20)->after('attention_point')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fair_gurunavi', function (Blueprint $table) {
            $table->dropColumn('event_kbn');
        });
    }
}
