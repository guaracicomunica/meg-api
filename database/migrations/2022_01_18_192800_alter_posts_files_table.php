<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPostsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts_files', function (Blueprint $table) {
            $table->rename('posts_attachments');
            $table->addColumn('boolean', 'is_external_link')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts_attachments', function (Blueprint $table) {
            $table->dropColumn('is_external_link');
            $table->rename('posts_files');
        });
    }
}
