<?php

namespace App\Filament\Participant\Widgets;

use App\Models\User;
use Auth;
use Filament\Widgets\Widget;

class ParticipantWidget extends Widget
{
    protected static bool $isDiscovered = false;
    
    protected static string $view = 'filament.participant.widgets.participant-widget';

    public function getUserStatus(): bool
    {
        $data = User::find(auth()->user()->id)->userDetail;

        if ($data == null) {
            return false;
        }

        return true;
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

    public function getProfileLink(): string
    {
        return url('/edit-user-detail');
    }

    public function isAdmin(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }
}
