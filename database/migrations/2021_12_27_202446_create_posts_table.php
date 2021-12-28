<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);

            $table->longText('body');

            $table->dateTime('deadline')->nullable();

            $table->integer('points')->default(0);

            $table->integer('coins')->default(0);

            $table->integer('xp')->default(0);

            $table->boolean('disabled')->default(false);

            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('classrooms');

            $table->unsignedBigInteger('post_type_id');
            $table->foreign('post_type_id')->references('id')->on('post_types');

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
        Schema::dropIfExists('posts');
    }
}
