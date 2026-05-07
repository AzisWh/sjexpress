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
        Schema::create('invoice_table', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->date('tanggal_invoice');
            $table->foreignId('pt_id')->constrained('pt_table')->cascadeOnDelete();
            $table->bigInteger('nominal_invoice')->nullable();
            $table->bigInteger('nominal_cair')->nullable();
            $table->enum('status', ['pending', 'cair'])->default('pending');
            $table->date('tanggal_cair')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_table');
    }
};
