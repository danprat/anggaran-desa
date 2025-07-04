<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatan';

    protected $fillable = [
        'tahun_id',
        'bidang',
        'nama_kegiatan',
        'pagu_anggaran',
        'sumber_dana',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'dibuat_oleh',
        'keterangan',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
        'waktu_mulai' => 'date',
        'waktu_selesai' => 'date',
    ];

    /**
     * Relationships
     */
    public function tahunAnggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'tahun_id');
    }

    public function pembuatKegiatan()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function realisasi()
    {
        return $this->hasMany(Realisasi::class);
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTahun($query, $tahunId)
    {
        return $query->where('tahun_id', $tahunId);
    }

    public function scopeByBidang($query, $bidang)
    {
        return $query->where('bidang', $bidang);
    }

    /**
     * Helper methods
     */
    public function getTotalRealisasi()
    {
        return $this->realisasi()->sum('jumlah_realisasi');
    }

    public function getPersentaseRealisasi()
    {
        $total = $this->getTotalRealisasi();
        return $this->pagu_anggaran > 0 ? ($total / $this->pagu_anggaran) * 100 : 0;
    }

    public function getSisaAnggaran()
    {
        return $this->pagu_anggaran - $this->getTotalRealisasi();
    }

    public function canBeRealized()
    {
        return $this->status === 'disetujui';
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'verifikasi']);
    }
}
