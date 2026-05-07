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
        Schema::create('pelanggan_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->date('tanggal_lahir');
            $table->string('nomor_pelanggan')->unique();
            $table->string('nomor_telfon')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan_table');
    }
};
