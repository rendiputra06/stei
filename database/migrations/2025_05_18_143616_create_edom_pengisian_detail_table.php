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
        Schema::create('edom_pengisian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengisian_id')->constrained('edom_pengisian')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('edom_pertanyaan')->onDelete('cascade');
            $table->unsignedTinyInteger('nilai')->nullable()->comment('Nilai 1-5 untuk skala likert');
            $table->text('jawaban_text')->nullable()->comment('Untuk pertanyaan jenis teks');
            $table->timestamps();

            // Memastikan hanya ada satu jawaban per pertanyaan per pengisian
            $table->unique(['pengisian_id', 'pertanyaan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edom_pengisian_detail');
    }
};
