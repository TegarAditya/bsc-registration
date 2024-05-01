<?php

namespace App\Filament\Participant\Widgets;

use App\Models\User;
use Auth;
use Filament\Widgets\Widget;

class ExamEntryWidget extends Widget
{
    protected static string $view = 'filament.participant.widgets.exam-entry-widget';

    protected static bool $isLazy = false;

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getUserStatus(): bool
    {
        $data = User::find(auth()->user()->id)->userDetail;

        if ($data == null) {
            return false;
        }

        return true;
    }

    public function getSchedule(): string
    {
        return '"Apr 30, 2024 09:30:00"';
    }

    public function getProfileLink(): string
    {
        return url('/edit-user-detail');
    }

    public function getExamLink(): string
    {
        $currentDateTime = date('M d, Y H:i:s');

        $userEmail = Auth::user()->email;

        $passkey = env('EXAM_PASSKEY', '123456');

        $link = 'https://cbt-bsc.bupin.id/ceklogin.php?username=' . $userEmail . '&password=' . $passkey;

        return $link;
    }
}
