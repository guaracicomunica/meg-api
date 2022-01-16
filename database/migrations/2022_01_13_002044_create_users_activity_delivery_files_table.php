<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersActivityDeliveryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_activities_delivered_files', function (Blueprint $table) {
            $table->id();

            $table->string('path');

            $table->unsignedBigInteger('user_activity_id');
            $table->foreign('user_activity_id')->references('id')->on('users_activities');

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
        Schema::dropIfExists('users_activities_delivered_files');
    }
}
