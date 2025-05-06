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
        Schema::create('tahun_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()->comment('Kode tahun akademik, contoh: 20231');
            $table->year('tahun')->comment('Tahun akademik, contoh: 2023');
            $table->enum('semester', ['Ganjil', 'Genap', 'Pendek'])->comment('Semester: Ganjil/Genap/Pendek');
            $table->string('nama')->comment('Nama lengkap, contoh: Semester Ganjil 2023/2024');
            $table->boolean('aktif')->default(false)->comment('Status aktif tahun akademik');
            $table->date('tanggal_mulai')->comment('Tanggal mulai tahun akademik');
            $table->date('tanggal_selesai')->comment('Tanggal selesai tahun akademik');
            $table->dateTime('krs_mulai')->nullable()->comment('Tanggal mulai periode KRS');
            $table->dateTime('krs_selesai')->nullable()->comment('Tanggal selesai periode KRS');
            $table->dateTime('nilai_mulai')->nullable()->comment('Tanggal mulai periode pengisian nilai');
            $table->dateTime('nilai_selesai')->nullable()->comment('Tanggal selesai periode pengisian nilai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_akademik');
    }
};
