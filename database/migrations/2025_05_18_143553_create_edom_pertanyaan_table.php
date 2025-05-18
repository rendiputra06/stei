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
        Schema::create('edom_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('edom_templates')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('jenis', ['likert_scale', 'text']);
            $table->unsignedTinyInteger('urutan')->default(1);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edom_pertanyaan');
    }
};
