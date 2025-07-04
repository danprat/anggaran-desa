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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_id')->constrained('tahun_anggaran')->onDelete('cascade');
            $table->string('bidang'); // pembangunan, pemberdayaan, dll
            $table->string('nama_kegiatan');
            $table->decimal('pagu_anggaran', 15, 2);
            $table->string('sumber_dana'); // DD, ADD, PADes, dll
            $table->date('waktu_mulai');
            $table->date('waktu_selesai');
            $table->enum('status', ['draft', 'verifikasi', 'disetujui', 'ditolak'])->default('draft');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa
            $table->index(['tahun_id', 'status']);
            $table->index('dibuat_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
