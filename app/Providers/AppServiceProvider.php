<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Agenda::class => \App\Policies\AgendaPolicy::class,
        \App\Models\Disposisi::class => \App\Policies\DisposisiPolicy::class,
        \App\Models\DokumenLain::class => \App\Policies\DokumenLainPolicy::class,
        \App\Models\KategoriDokumen::class => \App\Policies\KategoriDokumenPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\SumberDokumen::class => \App\Policies\SumberDokumenPolicy::class,
        \App\Models\SuratKeluar::class => \App\Policies\SuratKeluarPolicy::class,
        \App\Models\SuratMasuk::class => \App\Policies\SuratMasukPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Worker::class => \App\Policies\WorkerPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
