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
        Schema::create('bukti_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisasi_id')->constrained('realisasi')->onDelete('cascade');
            $table->string('file_path'); // Lokasi file di storage
            $table->string('file_name'); // Nama file asli
            $table->string('file_type'); // pdf, jpg, png, dll
            $table->integer('file_size')->nullable(); // Ukuran file dalam bytes
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index untuk performa
            $table->index('realisasi_id');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_files');
    }
};
