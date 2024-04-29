<?php

namespace App\Filament\Exports;

use App\Models\Participant;
use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class ParticipantExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    { 
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name')->label('Nama'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('phone_number')->label('Nomor telepon'),
            ExportColumn::make('userDetail.companion_phone_number')->label('Nomor telepon pendamping'),
            ExportColumn::make('userDetail.school')->label('Sekolah'),
            ExportColumn::make('userDetail.grade')->label('Jenjang'),
            ExportColumn::make('userDetail.type')->label('Tipe'),
            ExportColumn::make('userDetail.address')->label('Alamat'),
            ExportColumn::make('userDetail.province.name')->label('Provinsi'),
            ExportColumn::make('userDetail.city.name')->label('Kabupaten/Kota'),
            ExportColumn::make('created_at')->label('Dibuat pada'),
            ExportColumn::make('updated_at')->label('Diperbarui pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your participant export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
