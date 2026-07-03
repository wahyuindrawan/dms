<?php

namespace App\Policies;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('document-type.view-any');
    }

    public function view(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document-type.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('document-type.create');
    }

    public function update(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document-type.update');
    }

    public function delete(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document-type.delete');
    }

    public function restore(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document-type.update');
    }

    public function forceDelete(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document-type.delete');
    }
}
