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
            $table->string('password');
            $table->string('phone_num')->nullable();
            $table->string('platform_wallet_address')->unique();
            $table->string('user_wallet_address')->nullable();
            $table->string('withdraw_password')->nullable();
            $table->string('up_invite_code');
            $table->string('invite_code')->nullable();
            $table->integer('pid')->nullable();
            $table->string('path')->nullable();
            $table->enum('activation_status',['0','1'])->default('0');
            $table->integer('register_time');
            $table->integer('activate_time')->nullable();
            $table->integer('account_bonus')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->index(['platform_wallet_address','pid','path']);
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
