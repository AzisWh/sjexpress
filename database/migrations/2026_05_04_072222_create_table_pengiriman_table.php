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
        Schema::create('pengiriman_table', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pt_id')->constrained('pt_table')->cascadeOnDelete();
            $table->foreignId('armada_id')->constrained('armada_table')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('driver_table')->cascadeOnDelete();
            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained('invoice_table')
                ->nullOnDelete();
            $table->date('tanggal_ambil');
            $table->string('rute_from');
            $table->string('rute_to');
            $table->bigInteger('harga_pabrik');
            $table->bigInteger('harga_armada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_table');
    }
};
