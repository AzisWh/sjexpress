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
        Schema::dropIfExists("signature_table");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('signature_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('signature');
            $table->timestamps();
        });
    }
};
