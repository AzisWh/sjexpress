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
        Schema::table('invoice_table', function (Blueprint $table) {
            $table->foreignId('signature_id')
                ->nullable()
                ->constrained('signature_table')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_table', function (Blueprint $table) {
            // remove foreign key first
            $table->dropForeign(['signature_id']);
            $table->dropColumn('signature_id');

        });
    }
};
