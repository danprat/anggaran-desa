<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DesaProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'alamat_lengkap',
        'kepala_desa',
        'nip_kepala_desa',
        'periode_jabatan_mulai',
        'periode_jabatan_selesai',
        'logo_desa',
        'logo_kabupaten',
        'logo_provinsi',
        'website',
        'email',
        'telepon',
        'fax',
        'visi',
        'misi',
        'sejarah_singkat',
        'luas_wilayah',
        'jumlah_penduduk',
        'jumlah_kk',
        'batas_utara',
        'batas_selatan',
        'batas_timur',
        'batas_barat',
        'is_active',
        'additional_settings'
    ];

    protected $casts = [
        'periode_jabatan_mulai' => 'date',
        'periode_jabatan_selesai' => 'date',
        'luas_wilayah' => 'decimal:2',
        'jumlah_penduduk' => 'integer',
        'jumlah_kk' => 'integer',
        'is_active' => 'boolean',
        'additional_settings' => 'json'
    ];

    /**
     * Get the active desa profile
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Handle additional_settings accessor to prevent JSON decode errors
     */
    public function getAdditionalSettingsAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }

        return [];
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->alamat_lengkap,
            "Desa {$this->nama_desa}",
            "Kec. {$this->kecamatan}",
            "{$this->kabupaten}, {$this->provinsi}",
            $this->kode_pos
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get full desa name with administrative levels
     */
    public function getFullNameAttribute()
    {
        return "Desa {$this->nama_desa}, Kec. {$this->kecamatan}, {$this->kabupaten}";
    }

    /**
     * Get logo URL for desa
     */
    public function getLogoDesaUrlAttribute()
    {
        if ($this->logo_desa && Storage::disk('public')->exists($this->logo_desa)) {
            return Storage::disk('public')->url($this->logo_desa);
        }
        return asset('images/default-logo-desa.svg');
    }

    /**
     * Get logo URL for kabupaten
     */
    public function getLogoKabupatenUrlAttribute()
    {
        if ($this->logo_kabupaten && Storage::disk('public')->exists($this->logo_kabupaten)) {
            return Storage::disk('public')->url($this->logo_kabupaten);
        }
        return asset('images/default-logo-kabupaten.svg');
    }

    /**
     * Get logo URL for provinsi
     */
    public function getLogoProvinsiUrlAttribute()
    {
        if ($this->logo_provinsi && Storage::disk('public')->exists($this->logo_provinsi)) {
            return Storage::disk('public')->url($this->logo_provinsi);
        }
        return asset('images/default-logo-provinsi.svg');
    }

    /**
     * Get kepala desa with period info
     */
    public function getKepalaDesaInfoAttribute()
    {
        $info = $this->kepala_desa;

        if ($this->periode_jabatan_mulai && $this->periode_jabatan_selesai) {
            $info .= " (Periode: {$this->periode_jabatan_mulai->format('Y')} - {$this->periode_jabatan_selesai->format('Y')})";
        }

        return $info;
    }

    /**
     * Check if kepala desa period is still active
     */
    public function getIsPeriodActiveAttribute()
    {
        if (!$this->periode_jabatan_mulai || !$this->periode_jabatan_selesai) {
            return true; // No period set, assume active
        }

        $now = now();
        return $now->between($this->periode_jabatan_mulai, $this->periode_jabatan_selesai);
    }

    /**
     * Get boundaries as formatted string
     */
    public function getBoundariesAttribute()
    {
        return [
            'Utara' => $this->batas_utara,
            'Selatan' => $this->batas_selatan,
            'Timur' => $this->batas_timur,
            'Barat' => $this->batas_barat
        ];
    }

    /**
     * Get demographic info
     */
    public function getDemographicInfoAttribute()
    {
        return [
            'luas_wilayah' => $this->luas_wilayah ? number_format($this->luas_wilayah, 2) . ' Ha' : null,
            'jumlah_penduduk' => $this->jumlah_penduduk ? number_format($this->jumlah_penduduk) . ' jiwa' : null,
            'jumlah_kk' => $this->jumlah_kk ? number_format($this->jumlah_kk) . ' KK' : null
        ];
    }

    /**
     * Scope for active profiles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Boot method to handle file cleanup
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($profile) {
            // Clean up logo files when profile is deleted
            $logos = ['logo_desa', 'logo_kabupaten', 'logo_provinsi'];

            foreach ($logos as $logo) {
                if ($profile->$logo && Storage::disk('public')->exists($profile->$logo)) {
                    Storage::disk('public')->delete($profile->$logo);
                }
            }
        });
    }
}
