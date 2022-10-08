<?php

namespace App\Policies;

use App\Models\Pengembang;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengembangPolicy
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
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Pengembang $pengembang)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin')) {
            return TRUE;
        }

        if ($user->level == 'pengembang') {
            $sessionData = session()->get('pengembang');

            if ($sessionData->id == $pengembang->id) {
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
        return $user->hasRole('Root') || $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Pengembang $pengembang)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin')) {
            return TRUE;
        }

        if ($user->level == 'pengembang') {
            $sessionData = session()->get('pengembang');

            if ($sessionData->id == $pengembang->id) {
                return TRUE;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Pengembang $pengembang)
    {
        //
        return $user->hasRole('Root');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Pengembang $pengembang)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Pengembang $pengembang)
    {
        //
    }
}
