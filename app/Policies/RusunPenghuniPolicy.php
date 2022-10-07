<?php

namespace App\Policies;

use App\Models\RusunPenghuni;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RusunPenghuniPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RusunPenghuni $rusunPenghuni)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'rusun_penghuni') {
            $sessionData = session()->get('rusun_penghuni');

            if ($sessionData->id == $rusunPenghuni->id) {
                return TRUE;
            }
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RusunPenghuni $rusunPenghuni)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'rusun_penghuni') {
            $sessionData = session()->get('rusun_penghuni');

            if ($sessionData->id == $rusunPenghuni->id) {
                return TRUE;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RusunPenghuni $rusunPenghuni)
    {
        //
        return $user->hasRole('Root');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RusunPenghuni $rusunPenghuni)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RusunPenghuni $rusunPenghuni)
    {
        //
    }
}
