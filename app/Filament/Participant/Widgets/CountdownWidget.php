<?php

namespace App\Filament\Participant\Widgets;

use Filament\Widgets\Widget;

class CountdownWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 99;

    protected static string $view = 'filament.participant.widgets.countdown-widget';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
