<?php

namespace App\Filament\Admin\Resources\ParticipantResource\Widgets;

use App\Models\UserDetail;
use Filament\Widgets\ChartWidget;

class ParticipantGradeChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $userDetails = UserDetail::all();

        $groupedData = $userDetails->groupBy('grade')->map(fn ($userDetail) => $userDetail->count());

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
        return 'pie';
    }
}
