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
        Schema::create('foto_pengiriman_table', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengiriman_id')
                ->constrained('pengiriman_table')
                ->cascadeOnDelete();

            $table->string('file_path');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pengiriman_table');
    }
};
