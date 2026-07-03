<?php

namespace App\Policies;

use App\Models\Legacy\SumberDokumen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SumberDokumenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'pimpinan', 'tu']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SumberDokumen $sumberDokumen): bool
    {
        return in_array($user->role, ['admin', 'pimpinan', 'tu']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'tu']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SumberDokumen $sumberDokumen): bool
    {
        return in_array($user->role, ['admin', 'tu']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SumberDokumen $sumberDokumen): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SumberDokumen $sumberDokumen): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SumberDokumen $sumberDokumen): bool
    {
        return $user->role === 'admin';
    }
}
