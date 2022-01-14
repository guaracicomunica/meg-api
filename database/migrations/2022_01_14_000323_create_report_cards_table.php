<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();

            $table->decimal('average', 5, 2)->default(0);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('classrooms');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_cards');
    }
}
