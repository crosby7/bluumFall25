<?php

namespace App\Policies;

use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Item;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Only patients can view the list of items.
     * Nurses will not interact with items.
     * Patients need to see items to browse the shop.
     */
    public function viewAny(Nurse|Patient $user): bool
    {
        // Only patients can view the item list
        return $user instanceof Patient;
    }

    /**
     * Determine whether the user can view the model.
     *
     * Only patients can view individual item details.
     * Nurses will not interact with items.
     */
    public function view(Nurse|Patient $user, Item $item): bool
    {
        // Only patients can view item details
        return $user instanceof Patient;
    }

    /**
     * Determine whether the user can create models.
     *
     * Neither nurses nor patients can create items.
     * Item creation is an administrative function.
     */
    public function create(Nurse|Patient $user): bool
    {
        // Neither nurses nor patients can create items
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Neither nurses nor patients can update items.
     * Item updates are an administrative function.
     */
    public function update(Nurse|Patient $user, Item $item): bool
    {
        // Neither nurses nor patients can update items
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Neither nurses nor patients can delete items.
     * Item deletion is an administrative function.
     */
    public function delete(Nurse|Patient $user, Item $item): bool
    {
        // Neither nurses nor patients can delete items
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * Neither nurses nor patients can restore items.
     * Item restoration is an administrative function.
     */
    public function restore(Nurse|Patient $user, Item $item): bool
    {
        // Neither nurses nor patients can restore items
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * Neither nurses nor patients can permanently delete items.
     * Permanent deletion is an administrative function.
     */
    public function forceDelete(Nurse|Patient $user, Item $item): bool
    {
        // Neither nurses nor patients can force delete items
        return false;
    }

    /**
     * Determine whether the user can purchase the item.
     *
     * Only patients can purchase items.
     * Nurses do not interact with the shop.
     */
    public function purchase(Nurse|Patient $user, Item $item): bool
    {
        // Only patients can purchase items
        return $user instanceof Patient;
    }
}
