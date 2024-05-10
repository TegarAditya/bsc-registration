<?php

namespace App\Filament\Participant\Widgets;

use Auth;
use Filament\Widgets\Widget;

class DownloadAnswerExplanationWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static string $view = 'filament.participant.widgets.download-answer-explanation-widget';

    protected static string $title = 'Pembahasan Soal';

    public function getTitle(): string
    {
        return static::$title;
    }

    public function getUserStatus(): bool
    {
        return true;
    }

    public function getUserCategory(): string
    {
        $category = Auth::user()->userDetail->type;

        switch ($category) {
            case 'KSN':
                return 'Umum';
            case 'KSM':
                return 'Madrasah';
            default:
                return '';
        }
    }

    public function getUserLevel(): string
    {
        $level = Auth::user()->userDetail->grade;

        switch ($level) {
            case 'SD':
            case 'MI':
                return 'LEVEL 1';
            case 'SMP':
            case 'MTs':
                return 'LEVEL 2';
            case 'SMA':
            case 'MA':
                return 'LEVEL 3';
            default:
                return '';
        }
    }

    public function getAnswerExplanationLink(): string
    {
        $levelType = $this->getUserCategory() . '-' . $this->getUserLevel();

        switch ($levelType) {
            case 'Umum-LEVEL 1':
                return 'https://drive.google.com/file/d/1arIwEomgh54k2Aj9ZZ4QqHvtdAv0dGpM/view?usp=drive_link';
            case 'Umum-LEVEL 2':
                return 'https://drive.google.com/file/d/1UCTLTxpuUk1fk-06xEzXFBKZcjafhKYF/view?usp=drive_link';
            case 'Umum-LEVEL 3':
                return 'https://drive.google.com/file/d/1MIj3SsGTkrgB_KMdHa1aEJSPW07__wnG/view?usp=drive_link';
            case 'Madrasah-LEVEL 1':
                return 'https://drive.google.com/file/d/1jMep96zT8k4W4UlIQdOcP1FJ818_u4Hk/view?usp=drive_link';
            case 'Madrasah-LEVEL 2':
                return 'https://drive.google.com/file/d/1SOko30Wz_H1NG88pveFFBHcFyl9SYZOz/view?usp=drive_link';
            case 'Madrasah-LEVEL 3':
                return 'https://drive.google.com/file/d/1AMDYQ7AEDFdWNyTP9zr-OV0s6q3OXTON/view?usp=drive_link';
            default:
                return $levelType;
        }
    }

    public function checkLink(): void
    {
        dd($this->getAnswerExplanationLink());
    }
}
