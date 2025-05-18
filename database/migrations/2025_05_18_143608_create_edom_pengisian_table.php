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
        Schema::create('edom_pengisian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('edom_jadwal')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->foreignId('jadwal_kuliah_id')->constrained('jadwal');
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah');
            $table->date('tanggal_pengisian');
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamps();

            // Unique constraint untuk memastikan mahasiswa hanya mengisi sekali per dosen, matkul dan jadwal EDOM
            $table->unique(['jadwal_id', 'mahasiswa_id', 'jadwal_kuliah_id'], 'edom_jadwal_mahasiswa_jadwalkuliah_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edom_pengisian');
    }
};
