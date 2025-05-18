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
        Schema::create('edom_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->foreignId('template_id')->constrained('edom_templates');
            $table->string('nama_periode');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_aktif')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edom_jadwal');
    }
};
