<?php

namespace App\Filament\Participant\Widgets;

use Filament\Widgets\Widget;

class EventInfoWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;
    
    protected static string $view = 'filament.participant.widgets.event-info-widget';
}
