<?php

namespace App\Filament\Participant\Widgets;

use Filament\Widgets\Widget;

class ParticipantWidget extends Widget
{
    protected static string $view = 'filament.participant.widgets.participant-widget';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
