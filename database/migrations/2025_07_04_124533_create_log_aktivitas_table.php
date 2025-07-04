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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('aktivitas'); // Deskripsi aktivitas
            $table->string('tabel_terkait')->nullable(); // Optional: mis. 'kegiatan'
            $table->bigInteger('row_id')->nullable(); // Optional: id baris terkait
            $table->json('data_lama')->nullable(); // Data sebelum perubahan
            $table->json('data_baru')->nullable(); // Data setelah perubahan
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index(['user_id', 'created_at']);
            $table->index(['tabel_terkait', 'row_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
