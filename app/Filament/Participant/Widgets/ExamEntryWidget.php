<?php

namespace App\Filament\Participant\Widgets;

use App\Models\User;
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

        if ($currentDateTime == "Apr 30, 2024 09:00:00") {
            $currentDateTime = date('M d, Y H:i:s');
            return url('https://candidate.speedexam.net/openquiz.aspx?quiz=DF8960215A4B452F8E17505F0D417703');
        }

        return url('/');
    }
}
