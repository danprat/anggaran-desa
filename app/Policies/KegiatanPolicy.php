<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KegiatanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-kegiatan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kegiatan $kegiatan): bool
    {
        return $user->can('view-kegiatan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-kegiatan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kegiatan $kegiatan): bool
    {
        // Admin can edit any
        if ($user->hasRole('admin')) {
            return true;
        }

        // Operator can only edit their own draft/verifikasi kegiatan
        if ($user->hasRole('operator')) {
            return $user->can('edit-kegiatan')
                && $kegiatan->dibuat_oleh === $user->id
                && in_array($kegiatan->status, ['draft', 'verifikasi']);
        }

        // Sekretaris can edit draft/verifikasi kegiatan
        if ($user->hasRole('sekretaris')) {
            return $user->can('edit-kegiatan')
                && in_array($kegiatan->status, ['draft', 'verifikasi']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        // Admin can delete any
        if ($user->hasRole('admin')) {
            return true;
        }

        // Operator can only delete their own draft kegiatan
        if ($user->hasRole('operator')) {
            return $user->can('delete-kegiatan')
                && $kegiatan->dibuat_oleh === $user->id
                && $kegiatan->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can verify the model.
     */
    public function verify(User $user, Kegiatan $kegiatan): bool
    {
        return $user->can('verify-kegiatan')
            && $kegiatan->status === 'draft';
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Kegiatan $kegiatan): bool
    {
        return $user->can('approve-kegiatan')
            && $kegiatan->status === 'verifikasi';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kegiatan $kegiatan): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kegiatan $kegiatan): bool
    {
        return $user->hasRole('admin');
    }
}
