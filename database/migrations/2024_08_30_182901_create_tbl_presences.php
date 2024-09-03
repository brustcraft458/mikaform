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
        Schema::create('tbl_presences', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->timestamp('presence_at')->nullable();
            $table->unsignedBigInteger('id_template');
            $table->unsignedBigInteger('id_dump');
            $table->timestamps();

            $table->foreign('id_template')->references('id')->on('tbl_dumps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_dump')->references('id')->on('tbl_dumps')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_presence');
    }
};
