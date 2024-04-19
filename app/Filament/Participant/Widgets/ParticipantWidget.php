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

    public function getGroupLink(): string
    {
        if (in_array(auth()->user()->userDetail->grade, ['SD', 'MI'])) {
            return "https://chat.whatsapp.com/BsbizO7HZkRBHh25qC01ey";
        } else if (in_array(auth()->user()->userDetail->grade, ['SMP', 'MTs'])) {
            return "https://chat.whatsapp.com/CHJWfDpxTG7Kfi2u0DSVX9";
        } else if (in_array(auth()->user()->userDetail->grade, ['SMA', 'MA'])) {
            return "https://chat.whatsapp.com/DIHH1jWGhpQ5MDI80AwodL";
        }

        return false;
    }
}
