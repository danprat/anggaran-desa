<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class BuktiFile extends Model
{
    use HasFactory;

    protected $table = 'bukti_files';

    protected $fillable = [
        'realisasi_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Relationships
     */
    public function realisasi()
    {
        return $this->belongsTo(Realisasi::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Helper methods
     */
    public function getFileUrl()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function isImage()
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function isPdf()
    {
        return strtolower($this->file_type) === 'pdf';
    }

    public function deleteFile()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }

    /**
     * Boot method untuk auto delete file saat model dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($buktiFile) {
            $buktiFile->deleteFile();
        });
    }
}
