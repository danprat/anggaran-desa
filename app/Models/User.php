<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships
     */
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'dibuat_oleh');
    }

    public function realisasi()
    {
        return $this->hasMany(Realisasi::class, 'dibuat_oleh');
    }

    public function buktiFiles()
    {
        return $this->hasMany(BuktiFile::class, 'uploaded_by');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    /**
     * Helper methods
     */
    public function getRoleName()
    {
        return $this->roles->first()?->name ?? 'No Role';
    }

    public function hasRoleByName($role)
    {
        return $this->roles->contains('name', $role);
    }
}
