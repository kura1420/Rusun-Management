<?php

namespace App\Policies;

use App\Models\PengelolaDokumen;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengelolaDokumenPolicy
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
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PengelolaDokumen $pengelolaDokumen)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'pengelola') {
            $sessionData = session()->get('pengelola');

            if ($sessionData->id == $pengelolaDokumen->pengelola_id) {
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
        return $user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda') || $user->hasRole('Pengelola');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PengelolaDokumen $pengelolaDokumen)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'pengelola') {
            $sessionData = session()->get('pengelola');

            if ($sessionData->id == $pengelolaDokumen->pengelola_id) {
                return TRUE;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PengelolaDokumen $pengelolaDokumen)
    {
        //
        return $user->hasRole('Root');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PengelolaDokumen $pengelolaDokumen)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PengelolaDokumen $pengelolaDokumen)
    {
        //
    }
}
