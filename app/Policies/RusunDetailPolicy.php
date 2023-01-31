<?php

namespace App\Policies;

use App\Models\RusunDetail;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RusunDetailPolicy
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
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RusunDetail $rusunDetail)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'rusun') {
            $sessionData = session()->get('rusun');

            if ($sessionData->id == $rusunDetail->rusun_id) {
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
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda') || $user->hasRole('Rusun')) {
            return TRUE;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RusunDetail $rusunDetail)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'rusun') {
            $sessionData = session()->get('rusun');

            if ($sessionData->id == $rusunDetail->rusun_id) {
                return TRUE;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RusunDetail $rusunDetail)
    {
        //
        return $user->hasRole('Root');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RusunDetail $rusunDetail)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RusunDetail $rusunDetail)
    {
        //
    }
}
