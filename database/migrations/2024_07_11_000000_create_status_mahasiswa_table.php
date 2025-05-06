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
        Schema::create('status_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->enum('status', ['tidak_aktif', 'aktif', 'cuti', 'lulus', 'drop_out'])->default('tidak_aktif')->comment('Status: tidak aktif, aktif, cuti, lulus, drop out');
            $table->integer('semester')->comment('Semester mahasiswa (1, 2, dst)');
            $table->decimal('ip_semester', 3, 2)->nullable()->comment('IP semester');
            $table->decimal('ipk', 3, 2)->nullable()->comment('IPK');
            $table->integer('sks_semester')->default(0)->comment('SKS yang diambil pada semester ini');
            $table->integer('sks_total')->default(0)->comment('Total SKS yang telah diambil');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan');
            $table->unique(['mahasiswa_id', 'tahun_akademik_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_mahasiswa');
    }
};
