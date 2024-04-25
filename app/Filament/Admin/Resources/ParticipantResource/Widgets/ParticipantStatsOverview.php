<?php

namespace App\Filament\Admin\Resources\ParticipantResource\Widgets;

use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ParticipantStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftar', fn() => User::whereHas('roles', fn($query) => $query->where('name', 'participant'))->orWhereHas('userDetail')->count()),
            Stat::make('Persebaran Kabupaten/Kota', City::whereHas('userDetails', fn ($query) => $query->whereHas('user'))->count()),
            Stat::make('Persebaran Provinsi', Province::whereHas('userDetails', fn ($query) => $query->whereHas('user'))->count()),
        ];
    }
}
