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
        Schema::create('tbl_sections', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->enum('type', ['number', 'text', 'phone', 'file', 'payment']);
            $table->string('image')->nullable();
            $table->unsignedBigInteger('id_template');
            $table->timestamps();

            $table->foreign('id_template')->references('id')->on('tbl_template')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_sections');
    }
};
