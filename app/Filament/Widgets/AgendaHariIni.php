<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use Filament\Widgets\Widget;

class AgendaHariIni extends Widget
{
    protected static string $view = 'filament.widgets.agenda-hari-ini';

    protected int|string|array $columnSpan = 1;

    protected static bool $isLazy = false; // agar view langsung dirender dan bisa ambil data dalam view
}
