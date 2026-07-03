<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('document.view-any');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->hasPermission('document.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('document.create');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->hasPermission('document.update');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->hasPermission('document.delete');
    }

    public function restore(User $user, Document $document): bool
    {
        return $user->hasPermission('document.update');
    }

    public function forceDelete(User $user, Document $document): bool
    {
        return $user->hasPermission('document.delete');
    }
}
