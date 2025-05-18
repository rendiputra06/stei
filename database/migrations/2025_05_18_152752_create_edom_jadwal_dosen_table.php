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
        Schema::create('edom_jadwal_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edom_jadwal_id')->constrained('edom_jadwal')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->timestamps();

            // Buat unique key untuk mencegah duplikasi
            $table->unique(['edom_jadwal_id', 'dosen_id'], 'edom_jadwal_dosen_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edom_jadwal_dosen');
    }
};
