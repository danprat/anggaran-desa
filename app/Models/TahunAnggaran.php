<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAnggaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_anggaran';

    protected $fillable = [
        'tahun',
        'status',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    /**
     * Relationships
     */
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'tahun_id');
    }

    /**
     * Scopes
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Helper methods
     */
    public static function getTahunAktif()
    {
        return self::where('status', 'aktif')->first();
    }

    public function setAsAktif()
    {
        // Set semua tahun menjadi nonaktif
        self::where('status', 'aktif')->update(['status' => 'nonaktif']);

        // Set tahun ini sebagai aktif
        $this->update(['status' => 'aktif']);
    }
}
