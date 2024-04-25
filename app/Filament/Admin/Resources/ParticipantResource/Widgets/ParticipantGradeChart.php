<?php

namespace App\Filament\Admin\Resources\ParticipantResource\Widgets;

use App\Models\UserDetail;
use Filament\Widgets\ChartWidget;

class ParticipantGradeChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $userDetails = UserDetail::whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        })->get();

        $groupedData = $userDetails->groupBy('grade')->map(fn ($userDetail) => $userDetail->count());

        $dataValues = $groupedData->values()->toArray();

        $labels = $groupedData->keys()->toArray();

        $colors = [
            'SD' => '#ff0000', // Red
            'SMP' => '#00ff00', // Green
            'SMA' => '#0000ff', // Blue
            'MI' => '#ff0000', // Red
            'MTs' => '#00ff00', // Green
            'MA' => '#0000ff', // Blue
        ];

        $backgroundColors = array_map(function ($label) use ($colors) {
            return $colors[$label] ?? '#000000';
        }, $labels);

        return [
            'datasets' => [
                [
                    'label' => 'Data',
                    'data' => $dataValues,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
