<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_activities', function (Blueprint $table) {
            $table->id();

            $table->integer('points');

            $table->integer('xp');

            $table->integer('coins');

            $table->timestamp('delivered_at')->nullable();

            $table->timestamp('scored_at')->nullable();

            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('classrooms');

            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');

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
        Schema::dropIfExists('users_activities');
    }
}
