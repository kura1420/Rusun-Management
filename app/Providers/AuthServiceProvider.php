<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Pengembang::class => \App\Policies\PengembangPolicy::class,
        \App\Models\Pengelola::class => \App\Policies\PengelolaPolicy::class,
        \App\Models\Rusun::class => \App\Policies\RusunPolicy::class,
        \App\Models\RusunPenghuni::class => \App\Policies\RusunPenghuniPolicy::class,
        \App\Models\Pemilik::class => \App\Policies\PemilikPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
