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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_jp')->nullable();
            $table->string('code',50);
            //User ForeignKey
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            //City ForeignKey
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');

            $table->string('email');
            $table->string('telephone');
            $table->string('fax')->nullable();
            $table->string('address_1');
            $table->string('address_2')->nullable();
            
            //Tracking time
            $table->softDeletes();
            $table->timestamps();

            // Người tạo, cập nhật, xóa
            $table->unsignedBigInteger('create_user')->nullable();
            $table->string('create_name', 50)->nullable();
            $table->unsignedBigInteger('delete_user')->nullable();
            $table->string('delete_name', 50)->nullable();
            $table->unsignedBigInteger('update_user')->nullable();
            $table->string('update_name', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
