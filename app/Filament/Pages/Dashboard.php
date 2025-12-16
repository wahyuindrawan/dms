<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AgendaHariIni;
use App\Filament\Widgets\DisposisiBadge;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    // protected static ?string $title = 'Dashboard';
    // protected static ?string $navigationLabel = 'Dashboard';

    // protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            WelcomeWidget::class,
            DisposisiBadge::class,
            AgendaHariIni::class,
            StatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [];
    }

    public function getHeaderWidgetsColumns(): int | string | array
    {
        return 3;
    }
}
