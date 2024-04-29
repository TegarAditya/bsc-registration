<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class ParticipantStatistics extends Page 
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Data Peserta';

    protected static ?string $title = 'Statistik Peserta';

    protected static string $view = 'filament.admin.pages.participant-statistics';
}
