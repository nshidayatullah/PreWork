<?php

namespace App\Filament\Resources\PeriodReportResource\Pages;

use App\Filament\Resources\PeriodReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodReport extends EditRecord
{
    protected static string $resource = PeriodReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
