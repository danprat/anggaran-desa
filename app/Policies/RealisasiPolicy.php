<?php

namespace App\Policies;

use App\Models\Realisasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RealisasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-realisasi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Realisasi $realisasi): bool
    {
        return $user->can('view-realisasi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-realisasi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Realisasi $realisasi): bool
    {
        // Admin can edit any
        if ($user->hasRole('admin')) {
            return true;
        }

        // Bendahara can only edit their own realisasi
        if ($user->hasRole('bendahara')) {
            return $user->can('edit-realisasi')
                && $realisasi->dibuat_oleh === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Realisasi $realisasi): bool
    {
        // Admin can delete any
        if ($user->hasRole('admin')) {
            return true;
        }

        // Bendahara can only delete their own realisasi
        if ($user->hasRole('bendahara')) {
            return $user->can('delete-realisasi')
                && $realisasi->dibuat_oleh === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Realisasi $realisasi): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Realisasi $realisasi): bool
    {
        return $user->hasRole('admin');
    }
}
