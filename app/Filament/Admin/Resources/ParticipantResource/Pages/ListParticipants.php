<?php

namespace App\Filament\Admin\Resources\ParticipantResource\Pages;

use App\Filament\Admin\Resources\ParticipantResource;
use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantRegistrationChart;
use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantStatsOverview;
use App\Filament\Admin\Resources\ParticipantResource\Widgets\ParticipantTypeChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ParticipantStatsOverview::make(),
            // ParticipantRegistrationChart::make(),
            // ParticipantTypeChart::make(),
        ];
    }
}
