<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->string('role', 20)->default('member');
            $table->integer('referral')->unsigned()->default(0);
            $table->string('social_id', 200)->nullable();
            $table->string('social_type', 60)->nullable();
            $table->string('email_for_news')->nullable();
            $table->string('real_name', 100)->nullable();
            $table->string('description', 300)->nullable();
            $table->string('photo', 255)->nullable();
            $table->boolean('hide_upvotes')->default(0);
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
