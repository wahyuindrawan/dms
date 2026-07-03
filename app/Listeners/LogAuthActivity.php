<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;

class LogAuthActivity
{
    public function handleLogin(Login $event): void
    {
        activity()
            ->causedBy($event->user)
            ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
            ->log("Login berhasil: {$event->user->name}");
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            activity()
                ->causedBy($event->user)
                ->withProperties(['ip' => request()->ip()])
                ->log("Logout: {$event->user->name}");
        }
    }

    public function handleFailed(Failed $event): void
    {
        activity()
            ->withProperties([
                'ip'         => request()->ip(),
                'email'      => $event->credentials['email'] ?? null,
                'user_agent' => request()->userAgent(),
            ])
            ->log('Percobaan login gagal');
    }
}
