<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('description');
            // Thông tin tạo/cập nhật/xóa
            $table->softDeletes();
            $table->unsignedBigInteger('create_user')->nullable();
            $table->string('create_name', 50)->nullable();
            $table->unsignedBigInteger('delete_user')->nullable();
            $table->string('delete_name', 50)->nullable();
            $table->unsignedBigInteger('update_user')->nullable();
            $table->string('update_name', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
