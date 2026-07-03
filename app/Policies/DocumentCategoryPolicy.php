<?php

namespace App\Policies;

use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('document-category.view-any');
    }

    public function view(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->hasPermission('document-category.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('document-category.create');
    }

    public function update(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->hasPermission('document-category.update');
    }

    public function delete(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->hasPermission('document-category.delete');
    }

    public function restore(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->hasPermission('document-category.update');
    }

    public function forceDelete(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->hasPermission('document-category.delete');
    }
}
