<?php

namespace App\Filament\Admin\Resources\ParticipantResource\Widgets;

use App\Models\UserDetail;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class ParticipantTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $userDetails = UserDetail::all();

        $groupedData = $userDetails->groupBy('type')->map(fn ($userDetail) => $userDetail->count());

        $dataValues = $groupedData->values()->toArray();

        $labels = $groupedData->keys()->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Data',
                    'data' => $dataValues,
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
