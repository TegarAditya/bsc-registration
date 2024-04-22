<?php

namespace App\Filament\Admin\Resources\ParticipantResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ParticipantRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Peristiwa Daftar Peserta';

    protected static ?string $maxHeight = '300px';

    // protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Trend::query(User::whereHas('roles', fn ($query) => $query->where('name', 'participant'))->orWhereHas('userDetail'))
            ->between(
                start: now()->parse('2024-04-18'),
                end: now()->parse('2024-04-29'),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Pendaftar Baru',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => substr($value->date, -2)),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
