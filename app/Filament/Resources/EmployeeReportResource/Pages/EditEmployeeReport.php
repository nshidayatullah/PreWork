<?php

namespace App\Filament\Resources\EmployeeReportResource\Pages;

use App\Filament\Resources\EmployeeReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeReport extends EditRecord
{
    protected static string $resource = EmployeeReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
