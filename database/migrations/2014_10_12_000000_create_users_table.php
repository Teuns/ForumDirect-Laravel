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
            $table->integer('primary_role');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('user_avatar', 1000);
            $table->timestamp('user_timestamp');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('sex')->default('I prefer not to say');
            $table->date('date_of_birth')->nullable();
            $table->string('display_date_of_birth')->default('Display my age and date of birth');
            $table->text('bio')->nullable();
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
        Schema::dropIfExists('users');
    }
}
