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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah');
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->foreignId('ruangan_id')->constrained('ruangan');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('kelas', 10)->comment('Kelas paralel (A, B, C, dll)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Menambahkan indeks untuk mempercepat query
            $table->index('tahun_akademik_id');
            $table->index('mata_kuliah_id');
            $table->index('dosen_id');
            $table->index('ruangan_id');
            $table->index('hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
