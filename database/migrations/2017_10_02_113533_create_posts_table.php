<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('author_name', 255);
            $table->string('url', 200);
            $table->string('description_title', 400);
            $table->string('description_text', 2000);
            $table->string('description_image', 400);
            $table->string('category', 100)->nullable();
            $table->text('content');
            $table->string('type', 100);
            $table->string('state', 40)->default('publish');
            $table->string('tags', 1000);
            $table->string('options', 1000);
            $table->string('permission', 20)->default('public');
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
        Schema::drop('posts');
    }
}
