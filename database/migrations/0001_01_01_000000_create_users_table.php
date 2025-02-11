<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table-> id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->datetimes('day_of_birth');
            $table->string('avatar_url')->default('default_avatar.png');
            $table->datetimes('last_login_at');
            $table->datetimes('update_pass_date');
            $table->integer('update_pass_flg');
            $table->softDeletes();

            //Tracking xóa dữ liệu
            $table->unsignedBigInteger('delete_user')->nullable();
            $table->string('delete_name',50)->nullable();
            $table->integer('delete_flg')->default(0);

            //ForeignKey
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');

            // Thông tin tạo/cập nhật
            $table->timestamps();
            $table->unsignedBigInteger('create_user')->nullable();
            $table->string('create_name', 50)->nullable();
            $table->unsignedBigInteger('update_user')->nullable();
            $table->string('update_name', 10)->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
