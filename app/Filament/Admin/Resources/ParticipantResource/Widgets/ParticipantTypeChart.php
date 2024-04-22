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
        return [
            'datasets' => [
                [
                    'label' => 'Data',
                    'data' => UserDetail::all()->groupBy('type')->map(fn ($userDetail) => $userDetail->count())->values()->toArray(),
                ],
            ],
            'labels' => UserDetail::select('type')->groupBy('type')->pluck('type')->toArray(),
        ];        
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
