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
        \App\Models\Legacy\Agenda::class => \App\Policies\AgendaPolicy::class,
        \App\Models\Legacy\Disposisi::class => \App\Policies\DisposisiPolicy::class,
        \App\Models\Legacy\DokumenLain::class => \App\Policies\DokumenLainPolicy::class,
        \App\Models\Legacy\KategoriDokumen::class => \App\Policies\KategoriDokumenPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Legacy\SumberDokumen::class => \App\Policies\SumberDokumenPolicy::class,
        \App\Models\Legacy\SuratKeluar::class => \App\Policies\SuratKeluarPolicy::class,
        \App\Models\Legacy\SuratMasuk::class => \App\Policies\SuratMasukPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Legacy\Worker::class => \App\Policies\WorkerPolicy::class,
        \App\Models\Unit::class => \App\Policies\UnitPolicy::class,
        \App\Models\DocumentType::class => \App\Policies\DocumentTypePolicy::class,
        \App\Models\DocumentCategory::class => \App\Policies\DocumentCategoryPolicy::class,
        \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
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

        // Register auth activity listeners
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            [\App\Listeners\LogAuthActivity::class, 'handleLogin']
        );
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            [\App\Listeners\LogAuthActivity::class, 'handleLogout']
        );
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Failed::class,
            [\App\Listeners\LogAuthActivity::class, 'handleFailed']
        );
    }
}
