<?php

namespace App\Policies\MerchantNotification;

use App\Models\Admin;
use App\Models\MerchantNotification\MerchantNotification;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchantNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_merchant-notification');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $admin, MerchantNotification $merchantNotification): bool
    {
        return $admin->can('view_merchant-notification');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('create_merchant-notification');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, MerchantNotification $merchantNotification): bool
    {
        return $admin->can('update_merchant-notification');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, MerchantNotification $merchantNotification): bool
    {
        return $admin->can('delete_merchant-notification');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin $admin): bool
    {
        return $admin->can('delete_any_merchant-notification');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin $admin, MerchantNotification $merchantNotification): bool
    {
        return $admin->can('force_delete_merchant-notification');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin $admin): bool
    {
        return $admin->can('force_delete_any_merchant-notification');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin $admin, MerchantNotification $merchantNotification): bool
    {
        return $admin->can('restore_merchant-notification');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin $admin): bool
    {
        return $admin->can('restore_any_merchant-notification');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin $admin, MerchantNotification $merchantNotification): bool
    {
        return $admin->can('replicate_merchant-notification');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin $admin): bool
    {
        return $admin->can('reorder_merchant-notification');
    }
}
