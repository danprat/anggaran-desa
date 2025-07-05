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
        Schema::create('desa_profiles', function (Blueprint $table) {
            $table->id();

            // Basic Desa Information
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos', 10)->nullable();
            $table->text('alamat_lengkap');

            // Kepala Desa Information
            $table->string('kepala_desa');
            $table->string('nip_kepala_desa', 30)->nullable();
            $table->date('periode_jabatan_mulai')->nullable();
            $table->date('periode_jabatan_selesai')->nullable();

            // Logo Files
            $table->string('logo_desa')->nullable();
            $table->string('logo_kabupaten')->nullable();
            $table->string('logo_provinsi')->nullable();

            // Contact Information
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('fax', 20)->nullable();

            // Desa Profile
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah_singkat')->nullable();

            // Geographic Information
            $table->decimal('luas_wilayah', 10, 2)->nullable(); // in hectares
            $table->integer('jumlah_penduduk')->nullable();
            $table->integer('jumlah_kk')->nullable(); // Kepala Keluarga

            // Boundaries
            $table->string('batas_utara')->nullable();
            $table->string('batas_selatan')->nullable();
            $table->string('batas_timur')->nullable();
            $table->string('batas_barat')->nullable();

            // Additional Settings
            $table->boolean('is_active')->default(true);
            $table->json('additional_settings')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['nama_desa', 'kecamatan', 'kabupaten']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa_profiles');
    }
};
