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
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('username');
            $table->string('password');
            $table->enum('role', ['admin', 'member','super admin'])->default('member');
            $table->boolean('admin_request')->default(false); //buat user ngajuin jadi administrator
            $table->timestamp('verified_at')->nullable(); // Adding the verified_at column
            $table->string('otp')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
