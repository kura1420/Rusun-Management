<?php

namespace App\Policies;

use App\Models\PengembangKontak;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengembangKontakPolicy
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
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PengembangKontak $pengembangKontak)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'pengembang') {
            $sessionData = session()->get('pengembang');

            if ($sessionData->id == $pengembangKontak->pengembang_id) {
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
        return $user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda') || $user->hasRole('Pengembang');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PengembangKontak $pengembangKontak)
    {
        //
        if ($user->hasRole('Root') || $user->hasRole('Admin') || $user->hasRole('Pemda')) {
            return TRUE;
        }

        if ($user->level == 'pengembang') {
            $sessionData = session()->get('pengembang');

            if ($sessionData->id == $pengembangKontak->pengembang_id) {
                return TRUE;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PengembangKontak $pengembangKontak)
    {
        //
        return $user->hasRole('Root');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PengembangKontak $pengembangKontak)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PengembangKontak $pengembangKontak)
    {
        //
    }
}
