<?php

namespace App\Policies\Faq;

use App\Models\Admin;
use App\Models\Faq\Faq;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_faq');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $admin, Faq $faq): bool
    {
        return $admin->can('view_faq');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('create_faq');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, Faq $faq): bool
    {
        return $admin->can('update_faq');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, Faq $faq): bool
    {
        return $admin->can('delete_faq');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin $admin): bool
    {
        return $admin->can('delete_any_faq');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin $admin, Faq $faq): bool
    {
        return $admin->can('force_delete_faq');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin $admin): bool
    {
        return $admin->can('force_delete_any_faq');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin $admin, Faq $faq): bool
    {
        return $admin->can('restore_faq');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin $admin): bool
    {
        return $admin->can('restore_any_faq');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin $admin, Faq $faq): bool
    {
        return $admin->can('replicate_faq');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin $admin): bool
    {
        return $admin->can('reorder_faq');
    }
}
