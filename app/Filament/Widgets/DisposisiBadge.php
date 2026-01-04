<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DisposisiResource;
use App\Models\Disposisi;
use Filament\Widgets\Widget;

class DisposisiBadge extends Widget
{
    protected static string $view = 'filament.widgets.disposisi-badge';
    protected int | string | array $columnSpan = 1;
}
