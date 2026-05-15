<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_table', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by')->nullable()->after('nominal_cair');
            $table->string('verification_token')->nullable()->unique()->after('generated_by');

            $table->foreign('generated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invoice_table', function (Blueprint $table) {
            $table->dropForeign(['generated_by']);
            $table->dropColumn(['generated_by', 'verification_token']);
        });
    }
};
