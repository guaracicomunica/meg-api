<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitToActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
}
