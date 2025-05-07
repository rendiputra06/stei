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
        Schema::create('krs_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained()->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained()->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->integer('sks');
            $table->string('kelas');
            $table->string('status')->default('aktif')->comment('aktif, batal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Unique constraint - tidak boleh ada mata kuliah yang sama dalam satu KRS
            $table->unique(['krs_id', 'mata_kuliah_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs_detail');
    }
};
