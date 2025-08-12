<?php

namespace App\Filament\Resources\BloodPressureResource\Pages;

use App\Filament\Resources\BloodPressureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBloodPressure extends ViewRecord
{
    protected static string $resource = BloodPressureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
