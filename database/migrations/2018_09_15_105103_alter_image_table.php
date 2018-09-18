<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('image', function (Blueprint $table) {
        $table->integer('member_id')->after('id')->notnull();
        $table->string('rakuten_file_name', 100)->after('file_name')->nullable();
        $table->string('gurunavi_file_name', 100)->after('file_name')->nullable();
        $table->string('zexy_file_name', 100)->after('file_name')->nullable();
        $table->dropColumn('image_wedingpark_id');
        $table->dropColumn('image_mynavi_id');
        $table->dropColumn('image_minna_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
