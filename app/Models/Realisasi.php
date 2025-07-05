<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Realisasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'realisasi';

    protected $fillable = [
        'kegiatan_id',
        'jumlah_realisasi',
        'tanggal',
        'deskripsi',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah_realisasi' => 'decimal:2',
        'tanggal' => 'date',
    ];

    /**
     * Relationships
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function buktiFiles()
    {
        return $this->hasMany(BuktiFile::class);
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByKegiatan($query, $kegiatanId)
    {
        return $query->where('kegiatan_id', $kegiatanId);
    }

    /**
     * Helper methods
     */
    public function hasBukti()
    {
        return $this->buktiFiles()->count() > 0;
    }

    public function isComplete()
    {
        return $this->status === 'selesai';
    }

    public function canUploadBukti()
    {
        return $this->jumlah_realisasi > 0;
    }

    /**
     * Validation methods
     */
    public function validateRealisasiAmount()
    {
        $totalRealisasi = $this->kegiatan->realisasi()
            ->where('id', '!=', $this->id)
            ->sum('jumlah_realisasi');

        $newTotal = $totalRealisasi + $this->jumlah_realisasi;

        return $newTotal <= $this->kegiatan->pagu_anggaran;
    }
}
