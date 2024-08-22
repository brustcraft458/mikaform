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
        Schema::create('tbl_data', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->unsignedBigInteger('id_section');
            $table->unsignedBigInteger('id_dump');
            $table->timestamps();


            $table->foreign('id_section')->references('id')->on('tbl_sections')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_dump')->references('id')->on('tbl_dumps')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_data');
    }
};
