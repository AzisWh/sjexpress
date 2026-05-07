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
        Schema::create('pt_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pic');
            $table->string('no_pic');
            $table->string('alamat');
            $table->string('penagihan')->nullable();
            $table->string('no_penagihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt');
    }
};
