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
        // Hapus foreign key dari invoice_table terlebih dahulu
        Schema::table('invoice_table', function (Blueprint $table) {
            $table->dropForeign(['signature_id']);
            $table->dropColumn('signature_id');
        });

        // Kemudian hapus signature_table
        Schema::dropIfExists('signature_table');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rekreaasi signature_table
        Schema::create('signature_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('signature');
            $table->timestamps();
        });

        // Tambah kembali signature_id ke invoice_table
        Schema::table('invoice_table', function (Blueprint $table) {
            $table->foreignId('signature_id')
                ->nullable()
                ->constrained('signature_table')
                ->nullOnDelete();
        });
    }
};
